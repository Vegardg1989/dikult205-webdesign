"""The Rhetorics, World 2 (Chicago Rifle Association) data.

World 1 (The Crime Lab Project) is static pages only. As in Retrobaser, the
members are separate from django.contrib.auth so demo logins never reach the
host site's admin.
"""
from django.contrib.auth.hashers import check_password, make_password
from django.db import models


class Member(models.Model):
    name = models.CharField(max_length=60)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=128)
    is_admin = models.BooleanField(default=False)

    def __str__(self):
        return self.name

    def set_password(self, raw):
        self.password = make_password(raw)

    def check_password(self, raw):
        return check_password(raw, self.password)


class NewsPost(models.Model):
    title = models.CharField(max_length=120)
    body = models.TextField(max_length=3000)
    image = models.CharField(max_length=60, blank=True)  # file in static/rhetorics/pictures
    image_alt = models.CharField(max_length=120, blank=True)
    author = models.ForeignKey(Member, null=True, on_delete=models.SET_NULL)
    created = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ["-created"]


class Range(models.Model):
    name = models.CharField(max_length=80)
    area = models.CharField(max_length=80)
    description = models.TextField()

    class Meta:
        ordering = ["name"]

    @property
    def avg_rating(self):
        avg = self.ratings.aggregate(models.Avg("stars"))["stars__avg"]
        return round(avg, 1) if avg is not None else None

    @property
    def star_percent(self):
        avg = self.avg_rating
        return int(avg * 20) if avg is not None else 0


class RangeRating(models.Model):
    member = models.ForeignKey(Member, on_delete=models.CASCADE)
    range = models.ForeignKey(Range, on_delete=models.CASCADE, related_name="ratings")
    stars = models.PositiveSmallIntegerField()

    class Meta:
        constraints = [
            models.UniqueConstraint(fields=["member", "range"], name="rh_one_rating_per_member")
        ]


class DemoState(models.Model):
    reset_at = models.DateTimeField()
