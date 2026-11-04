from datetime import timedelta

from django.test import TestCase
from django.urls import reverse
from django.utils import timezone

from . import seed
from .models import BoardComment, DemoState, Game, GameComment, Member, Rating


class RetrobaserDemoTests(TestCase):
    def login(self, email="player@retrobaser.demo", password="player123"):
        return self.client.post(reverse("retrobaser:login"), {"email": email, "pass": password})

    def admin(self):
        return self.login("admin@retrobaser.demo", "admin123")

    def test_public_pages_render_and_seed(self):
        for name in ["index", "games", "board", "signup", "forgot_password"]:
            self.assertEqual(self.client.get(reverse(f"retrobaser:{name}")).status_code, 200, name)
        self.assertEqual(Game.objects.count(), len(seed.GAMES))
        game = Game.objects.get(name="Pac-Man")
        self.assertContains(self.client.get(reverse("retrobaser:game_detail", args=[game.pk])), "Pac-Man")

    def test_login_and_wrong_password(self):
        self.client.get(reverse("retrobaser:index"))
        self.login(password="wrong")
        self.assertNotContains(self.client.get(reverse("retrobaser:index")), "Welcome Player1")
        self.login()
        self.assertContains(self.client.get(reverse("retrobaser:index")), "Welcome Player1")

    def test_login_ignores_offsite_next(self):
        self.client.get(reverse("retrobaser:index"))
        r = self.client.post(reverse("retrobaser:login"),
                             {"email": "player@retrobaser.demo", "pass": "player123", "next": "https://evil.example/"})
        self.assertEqual(r["Location"], reverse("retrobaser:index"))

    def test_login_follows_local_next(self):
        self.client.get(reverse("retrobaser:index"))
        r = self.client.post(reverse("retrobaser:login"),
                             {"email": "player@retrobaser.demo", "pass": "player123", "next": "/demo/retrobaser/games/"})
        self.assertEqual(r["Location"], "/demo/retrobaser/games/")

    def test_rating_insert_then_update(self):
        self.client.get(reverse("retrobaser:index"))
        self.login()
        game = Game.objects.get(name="Warcraft III: Reign of Chaos")
        self.client.post(reverse("retrobaser:games"), {"video_game": game.pk, "rating_stars": 2})
        self.client.post(reverse("retrobaser:games"), {"video_game": game.pk, "rating_stars": 4})
        self.assertEqual(Rating.objects.get(game=game).stars, 4)
        r = self.client.post(reverse("retrobaser:games"), {"video_game": game.pk, "rating_stars": 9})
        self.assertEqual(r.status_code, 404)

    def test_comments_edit_own_and_admin_delete(self):
        self.client.get(reverse("retrobaser:index"))
        self.login()
        self.client.post(reverse("retrobaser:board"), {"action": "comment", "comment": "Add Turrican!"})
        c = BoardComment.objects.get(text="Add Turrican!")
        self.client.post(reverse("retrobaser:edit_board_comment", args=[c.pk]), {"comment": "Add Turrican II!"})
        c.refresh_from_db()
        self.assertEqual(c.text, "Add Turrican II!")
        other = BoardComment.objects.exclude(member__username="Player1").first()
        self.assertEqual(self.client.get(reverse("retrobaser:edit_board_comment", args=[other.pk])).status_code, 404)
        # members cannot delete
        self.client.post(reverse("retrobaser:board"), {"action": "delete-comment", "cid": other.pk})
        self.assertTrue(BoardComment.objects.filter(pk=other.pk).exists())
        self.client.get(reverse("retrobaser:logout"))
        self.admin()
        self.client.post(reverse("retrobaser:board"), {"action": "delete-comment", "cid": other.pk})
        self.assertFalse(BoardComment.objects.filter(pk=other.pk).exists())

    def test_game_comment(self):
        self.client.get(reverse("retrobaser:index"))
        self.login()
        game = Game.objects.get(name="Sonic the Hedgehog")
        self.client.post(reverse("retrobaser:game_detail", args=[game.pk]), {"action": "comment", "comment": "Gotta go fast"})
        self.assertTrue(GameComment.objects.filter(game=game, text="Gotta go fast").exists())

    def test_admin_pages_need_admin(self):
        self.client.get(reverse("retrobaser:index"))
        for name in ["admin_users", "admin_games"]:
            self.assertEqual(self.client.get(reverse(f"retrobaser:{name}")).status_code, 302)
        self.login()
        self.assertEqual(self.client.get(reverse("retrobaser:admin_users")).status_code, 302)
        self.client.get(reverse("retrobaser:logout"))
        self.admin()
        for name in ["admin_users", "admin_games"]:
            self.assertEqual(self.client.get(reverse(f"retrobaser:{name}")).status_code, 200)

    def test_admin_game_crud(self):
        self.client.get(reverse("retrobaser:index"))
        self.admin()
        data = {"game_name": "Turrican II", "game_description": "Amiga run and gun", "content": "Huge levels.",
                "game_developer": "Factor 5", "game_release_date": "1991-01-01"}
        self.client.post(reverse("retrobaser:admin_games"), data)
        game = Game.objects.get(name="Turrican II")
        data["game_name"] = "Turrican II: The Final Fight"
        self.client.post(reverse("retrobaser:edit_game", args=[game.pk]), data)
        game.refresh_from_db()
        self.assertEqual(game.name, "Turrican II: The Final Fight")
        self.client.post(reverse("retrobaser:delete_game", args=[game.pk]), {"sure": "Yes"})
        self.assertFalse(Game.objects.filter(pk=game.pk).exists())
        before = Game.objects.count()
        self.client.post(reverse("retrobaser:admin_games"), {"game_name": "Incomplete"})
        self.assertEqual(Game.objects.count(), before)

    def test_demo_logins_are_protected(self):
        self.client.get(reverse("retrobaser:index"))
        self.admin()
        player = Member.objects.get(email="player@retrobaser.demo")
        self.client.post(reverse("retrobaser:delete_user", args=[player.pk]), {"sure": "Yes"})
        self.assertTrue(Member.objects.filter(pk=player.pk).exists())
        self.client.post(reverse("retrobaser:edit_user", args=[player.pk]),
                         {"username": "Player1", "email": "hijack@example.com"})
        player.refresh_from_db()
        self.assertEqual(player.email, "player@retrobaser.demo")
        pete = Member.objects.get(username="PixelPete")
        self.client.post(reverse("retrobaser:delete_user", args=[pete.pk]), {"sure": "Yes"})
        self.assertFalse(Member.objects.filter(pk=pete.pk).exists())

    def test_change_password_keeps_demo_password(self):
        self.client.get(reverse("retrobaser:index"))
        self.login()
        self.client.post(reverse("retrobaser:change_password"), {"password": "newpass1", "password_repeat": "newpass1"})
        self.assertTrue(Member.objects.get(email="player@retrobaser.demo").check_password("player123"))

    def test_stale_demo_is_reset(self):
        self.client.get(reverse("retrobaser:index"))
        Game.objects.all().delete()
        DemoState.objects.update(reset_at=timezone.now() - timedelta(hours=25))
        self.client.get(reverse("retrobaser:games"))
        self.assertEqual(Game.objects.count(), len(seed.GAMES))
