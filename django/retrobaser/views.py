"""Retrobaser views: a Django rewrite of the 2020 PHP pages.

PHP file -> view
  index.php                     index
  login.php / logout.php        login / logout
  signup.php, activate.php      signup        (sign-up is switched off in the demo)
  forgot_password.php           forgot_password (no e-mail is sent in the demo)
  change_password.php           change_password
  games.php                     games         (sort + star rating)
  games/retrogames.php          game_detail   (rating + game comments)
  comments.php, editcomment.php board, edit_board_comment
  viewusers/edituser/deleteuser admin_users, edit_user, delete_user
  viewgames/editgames/deletegames admin_games, edit_game, delete_game
"""
from functools import wraps

from django.contrib import messages
from django.core.paginator import Paginator
from django.http import Http404
from django.shortcuts import get_object_or_404, redirect, render
from django.urls import reverse
from django.views.decorators.http import require_POST

from . import seed
from .models import BoardComment, Game, GameComment, Member, Rating

SESSION_KEY = "retrobaser_member_id"
MAX_TEXT = 500
MAX_POSTS = 300  # per reset, keeps the public demo from filling up

SORTS = {
    "title-ascending": "name",
    "title-descending": "-name",
    "created-descending": "-release_date",
    "created-ascending": "release_date",
}


# --- helpers -------------------------------------------------------------

def _safe_next(nxt):
    """Only follow `next` back into this demo (no open redirects)."""
    return nxt.startswith("/demo/retrobaser/") and "//" not in nxt and "\\" not in nxt


def demo_view(func):
    """Reset stale demo data, then attach the logged-in member (or None)."""

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
            return redirect("retrobaser:index")
        return func(request, *args, **kwargs)

    return demo_view(wrapper)


def admin_required(func):
    @wraps(func)
    def wrapper(request, *args, **kwargs):
        if request.member is None or not request.member.is_admin:
            # Same rule as the PHP pages: user_level 0 is sent back to the start page.
            messages.info(request, "That page is for admins.")
            return redirect("retrobaser:index")
        return func(request, *args, **kwargs)

    return demo_view(wrapper)


def page(request, template, **context):
    context.update(
        member=request.member,
        demo_logins=seed.DEMO_LOGINS,
        demo_reset_at=request.demo_reset_at,
    )
    return render(request, f"retrobaser/{template}", context)


def clean_text(request, field="comment"):
    text = (request.POST.get(field) or "").strip()
    if not text:
        messages.error(request, "You forgot to put in text.")
        return None
    if len(text) > MAX_TEXT:
        messages.error(request, f"Please keep it under {MAX_TEXT} characters.")
        return None
    if BoardComment.objects.count() + GameComment.objects.count() >= MAX_POSTS:
        messages.error(request, "The demo is full for today. It resets every 24 hours.")
        return None
    return text


def save_rating(request, game):
    try:
        stars = int(request.POST.get("rating_stars", ""))
    except ValueError:
        stars = 0
    if not 1 <= stars <= 5:
        raise Http404
    Rating.objects.update_or_create(
        member=request.member, game=game, defaults={"stars": stars}
    )
    messages.success(request, f"You gave {game.name} {stars} star{'s' if stars > 1 else ''}.")


# --- public pages ----------------------------------------------------------

@demo_view
def index(request):
    return page(request, "index.html")


@demo_view
@require_POST
def login(request):
    email = (request.POST.get("email") or "").strip().lower()
    password = request.POST.get("pass") or ""
    member = Member.objects.filter(email__iexact=email).first()
    if member and member.check_password(password):
        request.session.cycle_key()
        request.session[SESSION_KEY] = member.pk
        nxt = request.POST.get("next") or ""
        if _safe_next(nxt):
            return redirect(nxt)
        return redirect("retrobaser:index")
    messages.error(request, "The e-mail address and password do not match.")
    return redirect("retrobaser:index")


@demo_view
def logout(request):
    request.session.pop(SESSION_KEY, None)
    messages.info(request, "You are now logged out.")
    return redirect("retrobaser:index")


@demo_view
def signup(request):
    if request.method == "POST":
        messages.info(
            request,
            "Sign-up is switched off in this demo, so nothing was saved. "
            "Use one of the demo logins instead.",
        )
        return redirect("retrobaser:signup")
    return page(request, "signup.html")


@demo_view
def forgot_password(request):
    if request.method == "POST":
        messages.info(
            request,
            "In the original, this e-mailed a temporary password. "
            "The demo does not send e-mail. The demo logins are shown below.",
        )
        return redirect("retrobaser:forgot_password")
    return page(request, "forgot_password.html")


@member_required
def change_password(request):
    if request.method == "POST":
        new = request.POST.get("password") or ""
        repeat = request.POST.get("password_repeat") or ""
        if len(new) < 6:
            messages.error(request, "Password too short (at least 6 characters).")
        elif new != repeat:
            messages.error(request, "Your passwords did not match.")
        elif request.member.protected:
            messages.info(
                request,
                "The password checks passed, but shared demo accounts keep their "
                "password so the next visitor can log in.",
            )
        else:
            request.member.set_password(new)
            request.member.save()
            messages.success(request, "Your password has been changed.")
        return redirect("retrobaser:change_password")
    return page(request, "change_password.html")


@demo_view
def games(request):
    if request.method == "POST" and "rating_stars" in request.POST and request.member:
        save_rating(request, get_object_or_404(Game, pk=request.POST.get("video_game")))
        return redirect("retrobaser:games")
    sort = request.GET.get("sort", "title-ascending")
    qs = Game.objects.prefetch_related("ratings").order_by(SORTS.get(sort, "name"))
    return page(request, "games.html", games=qs, sort=sort)


@demo_view
def game_detail(request, pk):
    game = get_object_or_404(Game, pk=pk)
    if request.method == "POST" and request.member:
        action = request.POST.get("action")
        if action == "rate":
            save_rating(request, game)
        elif action == "comment":
            text = clean_text(request)
            if text:
                GameComment.objects.create(game=game, member=request.member, text=text)
        elif action == "delete-comment" and request.member.is_admin:
            GameComment.objects.filter(pk=request.POST.get("cid"), game=game).delete()
        return redirect("retrobaser:game_detail", pk=game.pk)
    return page(
        request,
        "game_detail.html",
        game=game,
        comments=game.comments.select_related("member"),
    )


@member_required
def edit_game_comment(request, pk):
    comment = get_object_or_404(GameComment, pk=pk, member=request.member)
    if request.method == "POST":
        text = clean_text(request)
        if text:
            comment.text = text
            comment.save()
            return redirect("retrobaser:game_detail", pk=comment.game_id)
    return page(request, "edit_comment.html", comment=comment,
                back_url=reverse("retrobaser:game_detail", args=[comment.game_id]))


@demo_view
def board(request):
    if request.method == "POST" and request.member:
        action = request.POST.get("action")
        if action == "comment":
            text = clean_text(request)
            if text:
                BoardComment.objects.create(member=request.member, text=text)
        elif action == "delete-comment" and request.member.is_admin:
            BoardComment.objects.filter(pk=request.POST.get("cid")).delete()
        return redirect("retrobaser:board")
    return page(request, "board.html", comments=BoardComment.objects.select_related("member"))


@member_required
def edit_board_comment(request, pk):
    comment = get_object_or_404(BoardComment, pk=pk, member=request.member)
    if request.method == "POST":
        text = clean_text(request)
        if text:
            comment.text = text
            comment.save()
            return redirect("retrobaser:board")
    return page(request, "edit_comment.html", comment=comment, back_url=reverse("retrobaser:board"))


# --- admin -----------------------------------------------------------------

USER_SORTS = {"un": "username", "em": "email", "rd": "-registered_at"}


@admin_required
def admin_users(request):
    sort = request.GET.get("sort", "rd")
    users = Paginator(Member.objects.order_by(USER_SORTS.get(sort, "-registered_at")), 5)
    return page(request, "admin_users.html", users=users.get_page(request.GET.get("page")), sort=sort)


@admin_required
def edit_user(request, pk):
    user = get_object_or_404(Member, pk=pk)
    if request.method == "POST":
        username = (request.POST.get("username") or "").strip()
        email = (request.POST.get("email") or "").strip().lower()
        errors = []
        if not username:
            errors.append("You forgot to enter a username.")
        if not email:
            errors.append("You forgot to enter an e-mail address.")
        if Member.objects.exclude(pk=user.pk).filter(username__iexact=username).exists():
            errors.append("That username is already taken.")
        if Member.objects.exclude(pk=user.pk).filter(email__iexact=email).exists():
            errors.append("That e-mail address is already registered.")
        if user.protected and (email != user.email or ("is_admin" in request.POST) != user.is_admin):
            errors.append("Demo logins keep their e-mail and role, so other visitors can still log in.")
        for e in errors:
            messages.error(request, e)
        if not errors:
            user.username = username
            user.email = email
            if not user.protected:
                user.is_admin = "is_admin" in request.POST
            user.save()
            messages.success(request, f"{user.username} has been updated.")
            return redirect("retrobaser:admin_users")
    return page(request, "edit_user.html", user=user)


@admin_required
def delete_user(request, pk):
    user = get_object_or_404(Member, pk=pk)
    if user.protected:
        messages.error(request, "Demo logins cannot be deleted. Try one of the other members.")
        return redirect("retrobaser:admin_users")
    if request.method == "POST":
        if request.POST.get("sure") == "Yes":
            name = user.username
            user.delete()
            messages.success(request, f"{name} has been deleted.")
        else:
            messages.info(request, "The user has NOT been deleted.")
        return redirect("retrobaser:admin_users")
    return page(request, "confirm_delete.html", thing=user.username, kind="user")


def _game_from_post(request, game=None):
    fields = {
        "name": ("game_name", "You forgot to enter a Game Title."),
        "short_description": ("game_description", "You forgot to enter a game description."),
        "content": ("content", "You forgot to enter the Long Description."),
        "developer": ("game_developer", "You forgot to enter a game developer."),
        "release_date": ("game_release_date", "You forgot to enter a release date."),
    }
    values, errors = {}, []
    for attr, (key, msg) in fields.items():
        v = (request.POST.get(key) or "").strip()
        if not v:
            errors.append(msg)
        values[attr] = v
    if errors:
        for e in errors:
            messages.error(request, e)
        return None
    game = game or Game()
    for attr, v in values.items():
        setattr(game, attr, v[:200] if attr != "content" else v[:2000])
    try:
        game.full_clean()
    except Exception:
        messages.error(request, "Please check the values (the release date must be a date).")
        return None
    game.save()
    return game


@admin_required
def admin_games(request):
    if request.method == "POST":
        if Game.objects.count() >= 40:
            messages.error(request, "The demo holds at most 40 games. It resets every 24 hours.")
        elif _game_from_post(request):
            messages.success(request, "Game added to the database!")
            return redirect("retrobaser:admin_games")
    games = Paginator(Game.objects.order_by("name"), 5)
    return page(request, "admin_games.html", games=games.get_page(request.GET.get("page")))


@admin_required
def edit_game(request, pk):
    game = get_object_or_404(Game, pk=pk)
    if request.method == "POST" and _game_from_post(request, game):
        messages.success(request, f"{game.name} has been updated.")
        return redirect("retrobaser:admin_games")
    return page(request, "edit_game.html", game=game)


@admin_required
def delete_game(request, pk):
    game = get_object_or_404(Game, pk=pk)
    if request.method == "POST":
        if request.POST.get("sure") == "Yes":
            name = game.name
            game.delete()
            messages.success(request, f"{name} has been deleted.")
        else:
            messages.info(request, "The game has NOT been deleted.")
        return redirect("retrobaser:admin_games")
    return page(request, "confirm_delete.html", thing=game.name, kind="game")
