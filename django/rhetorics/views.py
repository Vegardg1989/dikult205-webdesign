"""The Rhetorics: a Django rewrite of the 2020 PHP worlds.

World 1, The Crime Lab Project: home, research and crime reduction (static pages).
World 2, Chicago Rifle Association: home, news (admins add/edit/delete), gun-range
ratings for members, and the same login system as Retrobaser (e-mail + password,
sign-up, forgot password, change password, admin and member levels).
"""
from functools import wraps

from django.contrib import messages
from django.http import Http404
from django.shortcuts import get_object_or_404, redirect, render
from django.views.decorators.http import require_POST

from . import seed, snapshots
from .models import Member, NewsPost, Range, RangeRating

SESSION_KEY = "rhetorics_member_id"
PICTURES = ["", "range1.jpg", "range2.jpg", "cons.jpg", "eagle1.jpg", "chicago1.jpg"]
MAX_POSTS = 40


def _safe_next(nxt):
    """Only follow `next` back into this demo (no open redirects)."""
    return nxt.startswith("/demo/rhetorics/") and "//" not in nxt and "\\" not in nxt


def demo_view(func):
    @wraps(func)
    def wrapper(request, *args, **kwargs):
        request.demo_reset_at = seed.ensure_fresh()
        member_id = request.session.get(SESSION_KEY)
        request.member = Member.objects.filter(pk=member_id).first() if member_id else None
        return func(request, *args, **kwargs)

    return wrapper


def member_required(func):
    @wraps(func)
    def wrapper(request, *args, **kwargs):
        if request.member is None:
            messages.info(request, "Please log in first.")
            return redirect("rhetorics:login")
        return func(request, *args, **kwargs)

    return demo_view(wrapper)


def admin_required(func):
    @wraps(func)
    def wrapper(request, *args, **kwargs):
        if request.member is None or not request.member.is_admin:
            messages.info(request, "Only CRA admins can do that.")
            return redirect("rhetorics:news")
        return func(request, *args, **kwargs)

    return demo_view(wrapper)


def page(request, template, **context):
    context.update(
        member=getattr(request, "member", None),
        demo_logins=seed.DEMO_LOGINS,
        demo_reset_at=getattr(request, "demo_reset_at", None),
        snapshots=snapshots.BY_TEMPLATE.get(template, []),
        snapshot_note=snapshots.NOTE,
    )
    return render(request, f"rhetorics/{template}", context)


# --- landing + World 1 (static) ---------------------------------------------

@demo_view
def landing(request):
    return page(request, "landing.html")


def world1_home(request):
    return page(request, "world1/home.html")


def world1_research(request):
    return page(request, "world1/research.html")


def world1_crime_reduction(request):
    return page(request, "world1/crime_reduction.html")


# --- World 2: login system (same design as Retrobaser) ------------------------

@demo_view
def world2_home(request):
    return page(request, "world2/home.html")


@demo_view
def login(request):
    if request.method == "POST":
        email = (request.POST.get("email") or "").strip().lower()
        member = Member.objects.filter(email__iexact=email).first()
        if member and member.check_password(request.POST.get("password") or ""):
            request.session.cycle_key()
            request.session[SESSION_KEY] = member.pk
            messages.success(request, f"Welcome {member.name}!")
            nxt = request.POST.get("next") or ""
            return redirect(nxt if _safe_next(nxt) else "rhetorics:world2_home")
        messages.error(request, "The e-mail address and password do not match.")
        return redirect("rhetorics:login")
    return page(request, "world2/login.html")


@demo_view
def logout(request):
    request.session.pop(SESSION_KEY, None)
    messages.info(request, "You are now logged out.")
    return redirect("rhetorics:world2_home")


@demo_view
def signup(request):
    if request.method == "POST":
        messages.info(request, "Sign-up is switched off in this demo, so nothing was saved. "
                               "Use one of the demo logins instead.")
        return redirect("rhetorics:signup")
    return page(request, "world2/signup.html")


@demo_view
def forgot_password(request):
    if request.method == "POST":
        messages.info(request, "In the original, this e-mailed a temporary password. "
                               "The demo does not send e-mail. The demo logins are on the login page.")
        return redirect("rhetorics:forgot_password")
    return page(request, "world2/forgot_password.html")


@member_required
def change_password(request):
    if request.method == "POST":
        new = request.POST.get("password") or ""
        if len(new) < 6:
            messages.error(request, "Password too short (at least 6 characters).")
        elif new != (request.POST.get("password_repeat") or ""):
            messages.error(request, "Your passwords did not match.")
        else:
            messages.info(request, "The password checks passed, but shared demo accounts keep "
                                   "their password so the next visitor can log in.")
        return redirect("rhetorics:change_password")
    return page(request, "world2/change_password.html")


# --- World 2: news and ranges ---------------------------------------------------

@demo_view
def news(request):
    return page(request, "world2/news.html", posts=NewsPost.objects.select_related("author"))


def _post_from_form(request, post):
    title = (request.POST.get("title") or "").strip()[:120]
    body = (request.POST.get("body") or "").strip()[:3000]
    image = request.POST.get("image") or ""
    if not title or not body:
        messages.error(request, "A news post needs both a title and some text.")
        return False
    if image not in PICTURES:
        raise Http404
    post.title, post.body, post.image = title, body, image
    post.image_alt = "" if not image else post.image_alt or "Picture for the news post"
    post.author = post.author or request.member
    post.save()
    return True


@admin_required
def news_new(request):
    post = NewsPost()
    if request.method == "POST":
        if NewsPost.objects.count() >= MAX_POSTS:
            messages.error(request, "The demo is full for today. It resets every 24 hours.")
        elif _post_from_form(request, post):
            messages.success(request, "The news post is published.")
            return redirect("rhetorics:news")
    return page(request, "world2/news_form.html", post=post, pictures=PICTURES, heading="Write a news post")


@admin_required
def news_edit(request, pk):
    post = get_object_or_404(NewsPost, pk=pk)
    if request.method == "POST" and _post_from_form(request, post):
        messages.success(request, "The news post is updated.")
        return redirect("rhetorics:news")
    return page(request, "world2/news_form.html", post=post, pictures=PICTURES, heading="Edit the news post")


@admin_required
def news_delete(request, pk):
    post = get_object_or_404(NewsPost, pk=pk)
    if request.method == "POST":
        post.delete()
        messages.success(request, "The news post is deleted.")
        return redirect("rhetorics:news")
    return page(request, "world2/news_delete.html", post=post)


@demo_view
def ranges(request):
    if request.method == "POST":
        if request.member is None:
            messages.info(request, "Log in as a member to rate a range.")
            return redirect("rhetorics:ranges")
        rng = get_object_or_404(Range, pk=request.POST.get("range"))
        try:
            stars = int(request.POST.get("stars", ""))
        except ValueError:
            stars = 0
        if not 1 <= stars <= 5:
            raise Http404
        RangeRating.objects.update_or_create(member=request.member, range=rng, defaults={"stars": stars})
        messages.success(request, f"Thanks! You gave {rng.name} {stars} star{'s' if stars > 1 else ''}.")
        return redirect("rhetorics:ranges")
    mine = {}
    if request.member:
        mine = dict(RangeRating.objects.filter(member=request.member).values_list("range_id", "stars"))
    rows = [(r, mine.get(r.pk)) for r in Range.objects.prefetch_related("ratings")]
    return page(request, "world2/ranges.html", rows=rows)
