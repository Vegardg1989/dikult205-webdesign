from django.test import TestCase
from django.urls import reverse

from .models import NewsPost, Range, RangeRating


class RhetoricsDemoTests(TestCase):
    def login(self, email="member@cra.demo", password="member123"):
        return self.client.post(reverse("rhetorics:login"), {"email": email, "password": password})

    def test_pages_render(self):
        for name in ["landing", "world1_home", "world1_research", "world2_home", "news", "ranges"]:
            self.assertEqual(self.client.get(reverse(f"rhetorics:{name}")).status_code, 200, name)
        self.assertEqual(NewsPost.objects.count(), 3)

    def test_member_rates_range(self):
        self.client.get(reverse("rhetorics:landing"))
        self.login()
        rng = Range.objects.get(name="Riverbend Training Center")
        self.client.post(reverse("rhetorics:ranges"), {"range": rng.pk, "stars": 3})
        self.client.post(reverse("rhetorics:ranges"), {"range": rng.pk, "stars": 5})
        self.assertEqual(RangeRating.objects.get(range=rng, member__email="member@cra.demo").stars, 5)

    def test_guest_cannot_rate(self):
        self.client.get(reverse("rhetorics:landing"))
        rng = Range.objects.first()
        before = RangeRating.objects.count()
        self.client.post(reverse("rhetorics:ranges"), {"range": rng.pk, "stars": 3})
        self.assertEqual(RangeRating.objects.count(), before)

    def test_news_admin_only(self):
        self.client.get(reverse("rhetorics:landing"))
        self.login()
        self.client.post(reverse("rhetorics:news_new"), {"title": "Hi", "body": "x", "image": ""})
        self.assertFalse(NewsPost.objects.filter(title="Hi").exists())
        self.client.get(reverse("rhetorics:logout"))
        self.login("admin@cra.demo", "admin123")
        self.client.post(reverse("rhetorics:news_new"), {"title": "Range day", "body": "Saturday.", "image": "range2.jpg"})
        post = NewsPost.objects.get(title="Range day")
        self.client.post(reverse("rhetorics:news_edit", args=[post.pk]), {"title": "Range day!", "body": "Sunday.", "image": ""})
        post.refresh_from_db()
        self.assertEqual((post.title, post.image), ("Range day!", ""))
        r = self.client.post(reverse("rhetorics:news_edit", args=[post.pk]), {"title": "x", "body": "y", "image": "../../secret"})
        self.assertEqual(r.status_code, 404)
        self.client.post(reverse("rhetorics:news_delete", args=[post.pk]))
        self.assertFalse(NewsPost.objects.filter(pk=post.pk).exists())
