"""Seed data and the 24-hour reset for the public demo.

All organisations, ranges and posts in World 2 are fictional.
"""
from datetime import timedelta

from django.db import transaction
from django.utils import timezone

from .models import DemoState, Member, NewsPost, Range, RangeRating

RESET_EVERY = timedelta(hours=24)

DEMO_LOGINS = [
    {"role": "Admin", "email": "admin@cra.demo", "password": "admin123"},
    {"role": "Member", "email": "member@cra.demo", "password": "member123"},
]

MEMBERS = [
    ("CRA Admin", "admin@cra.demo", "admin123", True),
    ("Demo Member", "member@cra.demo", "member123", False),
    ("Hank", "hank@cra.demo", "not-a-login-1", False),
    ("Dolores", "dolores@cra.demo", "not-a-login-2", False),
    ("Earl", "earl@cra.demo", "not-a-login-3", False),
]

NEWS = [
    (
        "Responsible ownership starts with safe storage",
        "A recent story in the Chicago Tribune told of a toddler who was badly hurt while his parents "
        "struggled over a gun. Stories like this are why the CRA exists. The right to keep and bear arms "
        "comes with a duty to store firearms safely and to keep them out of children's reach.\n\n"
        "Our spring safety course covers locked storage, safe handling and what to do in a conflict at home. "
        "Members attend for free.",
        "range1.jpg",
        "A shooter aiming at a paper target on an indoor range",
    ),
    (
        "Know your rights: the Second Amendment",
        "\"A well regulated Militia, being necessary to the security of a free State, the right of the people "
        "to keep and bear Arms, shall not be infringed.\" Generations of Americans have defended these words. "
        "The CRA works so that the next generation inherits the same freedom.\n\n"
        "Join our evening seminar on the history of the amendment and what it means for gun owners in Illinois today.",
        "cons.jpg",
        "\"We the People\" from the U.S. Constitution seen through a magnifying glass",
    ),
    (
        "Spring hunting season: sportsmanship first",
        "Conservation and safe sport hunting go hand in hand. Before the season starts, review the rules for "
        "your area, check your equipment and remember that good fellowship in the field matters as much as "
        "a good shot.",
        "eagle1.jpg",
        "A bald eagle with spread wings",
    ),
]

RANGES = [
    ("Lakeshore Indoor Range", "North Side", "Twelve lanes, climate controlled, with certified range officers every day."),
    ("Prairie Sportsmen's Club", "South Suburbs", "Outdoor rifle range up to 200 yards. Family days on the first Sunday of the month."),
    ("Riverbend Training Center", "West Side", "Beginner-friendly range that hosts the CRA safety courses."),
]

RATINGS = {  # range -> stars from Hank, Dolores, Earl
    "Lakeshore Indoor Range": [5, 4, 5],
    "Prairie Sportsmen's Club": [4, 3, 4],
    "Riverbend Training Center": [5, 5],
}


@transaction.atomic
def reset():
    for model in (RangeRating, NewsPost, Range, Member):
        model.objects.all().delete()
    members = {}
    for name, email, password, is_admin in MEMBERS:
        m = Member(name=name, email=email, is_admin=is_admin)
        m.set_password(password)
        m.save()
        members[name] = m
    now = timezone.now()
    for i, (title, body, image, alt) in enumerate(NEWS):
        post = NewsPost.objects.create(
            title=title, body=body, image=image, image_alt=alt, author=members["CRA Admin"]
        )
        NewsPost.objects.filter(pk=post.pk).update(created=now - timedelta(days=3 * i))
    ranges = {}
    for name, area, desc in RANGES:
        ranges[name] = Range.objects.create(name=name, area=area, description=desc)
    for name, stars in RATINGS.items():
        for who, s in zip(["Hank", "Dolores", "Earl"], stars):
            RangeRating.objects.create(member=members[who], range=ranges[name], stars=s)
    DemoState.objects.all().delete()
    DemoState.objects.create(reset_at=now)


def ensure_fresh():
    state = DemoState.objects.order_by("-reset_at").first()
    if state is None or timezone.now() - state.reset_at > RESET_EVERY:
        reset()
        return DemoState.objects.get().reset_at
    return state.reset_at
