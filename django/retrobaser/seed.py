"""Seed data and the 24-hour reset for the public demo."""
from datetime import date, timedelta

from django.db import transaction
from django.utils import timezone

from .models import BoardComment, DemoState, Game, GameComment, GameImage, Member, Rating

RESET_EVERY = timedelta(hours=24)

# Shown on the site so visitors can log in.
DEMO_LOGINS = [
    {"role": "Admin", "email": "admin@retrobaser.demo", "password": "admin123"},
    {"role": "Member", "email": "player@retrobaser.demo", "password": "player123"},
]

MEMBERS = [
    # username, email, password, is_admin, protected
    ("RetroAdmin", "admin@retrobaser.demo", "admin123", True, True),
    ("Player1", "player@retrobaser.demo", "player123", False, True),
    ("PixelPete", "pete@retrobaser.demo", "not-a-login-1", False, False),
    ("AmigaAnna", "anna@retrobaser.demo", "not-a-login-2", False, False),
    ("SegaSam", "sam@retrobaser.demo", "not-a-login-3", False, False),
    ("JoystickJo", "jo@retrobaser.demo", "not-a-login-4", False, False),
    ("Cartridge", "cart@retrobaser.demo", "not-a-login-5", False, False),
]

# Release years are what the list shows; the day is only used for sorting.
GAMES = [
    {
        "name": "Pac-Man",
        "short_description": "The maze chase that started it all",
        "content": "Guide Pac-Man through the maze, eat every dot and stay away from Blinky, "
        "Pinky, Inky and Clyde until a power pellet turns the tables.",
        "developer": "Namco",
        "release_date": date(1980, 5, 22),
        "image": ("pac-man.jpg", "Pac-Man"),
    },
    {
        "name": "Super Mario Bros. 3",
        "short_description": "Mario's biggest NES adventure",
        "content": "Eight worlds, a map screen, the Tanooki suit and the Koopalings. "
        "Many still call it the best platformer on the NES.",
        "developer": "Nintendo",
        "release_date": date(1988, 10, 23),
        "image": ("super-mario3.jpg", "Super Mario Bros. 3"),
    },
    {
        "name": "Sonic the Hedgehog",
        "short_description": "Sega's answer to Mario, at full speed",
        "content": "Loops, springs and golden rings. Sonic races through Green Hill Zone "
        "and beyond to stop Dr. Robotnik.",
        "developer": "Sega",
        "release_date": date(1991, 6, 23),
        "image": ("sonic.jpg", "Sonic the Hedgehog"),
    },
    {
        "name": "Lotus Turbo Challenge 2",
        "short_description": "Split-screen racing on the Amiga",
        "content": "Race a Lotus through fog, snow, desert and night stages against the "
        "clock, or against a friend in split screen.",
        "developer": "Magnetic Fields / Gremlin Graphics",
        "release_date": date(1991, 1, 1),
        "image": ("lotus2.jpg", "Lotus Turbo Challenge 2"),
    },
    {
        "name": "Warcraft: Orcs & Humans",
        "short_description": "Where the Warcraft story began",
        "content": "Real-time strategy set in Azeroth: gather gold and lumber, build a base "
        "and command orcs or humans.",
        "developer": "Blizzard Entertainment",
        "release_date": date(1994, 11, 23),
        "image": ("warcraft.jpg", "Warcraft: Orcs & Humans"),
    },
    {
        "name": "Warcraft II: Tides of Darkness",
        "short_description": "Naval battles, dragons and LAN parties",
        "content": "The sequel added sea and air units, fog of war and multiplayer that "
        "kept LAN parties going for years.",
        "developer": "Blizzard Entertainment",
        "release_date": date(1995, 12, 9),
        "image": ("warcraft2.jpg", "Warcraft II: Tides of Darkness"),
    },
    {
        "name": "Warcraft III: Reign of Chaos",
        "short_description": "Heroes, four races and a modding scene",
        "content": "Four playable races, hero units and a map editor whose custom maps "
        "gave birth to whole new genres.",
        "developer": "Blizzard Entertainment",
        "release_date": date(2002, 7, 3),
        "image": ("warcraft3.jpg", "Warcraft III: Reign of Chaos"),
    },
]

RATINGS = {  # game name -> stars from PixelPete, AmigaAnna, SegaSam, JoystickJo
    "Pac-Man": [5, 4, 4, 5],
    "Super Mario Bros. 3": [5, 5, 4, 5],
    "Sonic the Hedgehog": [4, 3, 5, 4],
    "Lotus Turbo Challenge 2": [4, 5, 3],
    "Warcraft: Orcs & Humans": [3, 4],
    "Warcraft II: Tides of Darkness": [5, 4, 5],
}

BOARD = [
    ("PixelPete", "Suggestion: could we get Turrican II? Best Amiga soundtrack ever."),
    ("AmigaAnna", "Seconding Turrican II. And maybe Sensible Soccer?"),
    ("SegaSam", "Streets of Rage 2 belongs here. Yuzo Koshiro's music alone earns it a spot."),
]

GAME_COMMENTS = [
    ("Lotus Turbo Challenge 2", "AmigaAnna", "The fog stage with a friend in split screen. Pure chaos."),
    ("Pac-Man", "JoystickJo", "Still can't get past level 10 without losing my mind."),
    ("Warcraft II: Tides of Darkness", "PixelPete", "Zug zug. Those LAN parties were the best."),
]


@transaction.atomic
def reset():
    for model in (GameComment, BoardComment, Rating, GameImage, Game, Member):
        model.objects.all().delete()

    members = {}
    for username, email, password, is_admin, protected in MEMBERS:
        m = Member(username=username, email=email, is_admin=is_admin, protected=protected)
        m.set_password(password)
        m.save()
        members[username] = m

    games = {}
    for g in GAMES:
        filename, alt = g["image"]
        game = Game.objects.create(**{k: v for k, v in g.items() if k != "image"})
        GameImage.objects.create(game=game, filename=filename, description=alt)
        games[game.name] = game

    raters = ["PixelPete", "AmigaAnna", "SegaSam", "JoystickJo"]
    for name, stars in RATINGS.items():
        for who, s in zip(raters, stars):
            Rating.objects.create(member=members[who], game=games[name], stars=s)

    for who, text in BOARD:
        BoardComment.objects.create(member=members[who], text=text)
    for game, who, text in GAME_COMMENTS:
        GameComment.objects.create(game=games[game], member=members[who], text=text)

    DemoState.objects.all().delete()
    DemoState.objects.create(reset_at=timezone.now())


def ensure_fresh():
    """Reset the demo if it has never been seeded or the last reset is >24 h old."""
    state = DemoState.objects.order_by("-reset_at").first()
    if state is None or timezone.now() - state.reset_at > RESET_EVERY:
        reset()
        return DemoState.objects.get().reset_at
    return state.reset_at
