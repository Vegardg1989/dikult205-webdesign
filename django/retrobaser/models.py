"""Retrobaser data model.

Mirrors the 2020 MySQL tables: users, videogame, images, videogame_ratings,
comments (the Discuss/Suggest board) and videogame_comments.

Members are kept separate from django.contrib.auth on purpose: this is a public
demo, and its accounts must never be able to reach the host site's admin.
"""
from django.contrib.auth.hashers import check_password, make_password
from django.db import models


class Member(models.Model):
    username = models.CharField(max_length=40, unique=True)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=128)
    is_admin = models.BooleanField(default=False)  # user_level 1 in the PHP version
    protected = models.BooleanField(
        default=False, help_text="Shared demo login: cannot be deleted or re-keyed."
    )
    registered_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ["-registered_at"]

    def __str__(self):
        return self.username

    def set_password(self, raw):
        self.password = make_password(raw)

    def check_password(self, raw):
        return check_password(raw, self.password)


class Game(models.Model):
    name = models.CharField(max_length=80)
    short_description = models.CharField(max_length=200)
    content = models.TextField()
    developer = models.CharField(max_length=80)
    release_date = models.DateField()

    def __str__(self):
        return self.name

    @property
    def avg_rating(self):
        avg = self.ratings.aggregate(models.Avg("stars"))["stars__avg"]
        return round(avg, 1) if avg is not None else None

    @property
    def star_image(self):
        """Same thresholds as games.php: 20/40/60/80/100 % star strips."""
        avg = self.avg_rating
        if avg is None:
            return None
        for limit, pct in ((1.5, 20), (2.5, 40), (3.5, 60), (4.5, 80)):
            if avg < limit:
                return f"stars{pct}.svg"
        return "stars100.svg"


class GameImage(models.Model):
    game = models.ForeignKey(Game, on_delete=models.CASCADE, related_name="images")
    filename = models.CharField(max_length=100)  # file in static/retrobaser/images
    description = models.CharField(max_length=200, blank=True)


class Rating(models.Model):
    member = models.ForeignKey(Member, on_delete=models.CASCADE)
    game = models.ForeignKey(Game, on_delete=models.CASCADE, related_name="ratings")
    stars = models.PositiveSmallIntegerField()

    class Meta:
        constraints = [
            models.UniqueConstraint(fields=["member", "game"], name="rb_one_rating_per_member")
        ]


class BoardComment(models.Model):
    """Discuss/Suggest board (comments table)."""

    member = models.ForeignKey(Member, on_delete=models.CASCADE)
    created = models.DateTimeField(auto_now_add=True)
    text = models.TextField(max_length=500)

    class Meta:
        ordering = ["-created"]


class GameComment(models.Model):
    """Comments on a single game (videogame_comments table)."""

    game = models.ForeignKey(Game, on_delete=models.CASCADE, related_name="comments")
    member = models.ForeignKey(Member, on_delete=models.CASCADE)
    created = models.DateTimeField(auto_now_add=True)
    text = models.TextField(max_length=500)

    class Meta:
        ordering = ["-created"]


class DemoState(models.Model):
    """One row: when the demo data was last reset to the seed."""

    reset_at = models.DateTimeField()
