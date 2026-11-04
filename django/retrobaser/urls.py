from django.urls import path

from . import views

app_name = "retrobaser"

urlpatterns = [
    path("", views.index, name="index"),
    path("login/", views.login, name="login"),
    path("logout/", views.logout, name="logout"),
    path("signup/", views.signup, name="signup"),
    path("forgot-password/", views.forgot_password, name="forgot_password"),
    path("change-password/", views.change_password, name="change_password"),
    path("games/", views.games, name="games"),
    path("games/<int:pk>/", views.game_detail, name="game_detail"),
    path("games/comments/<int:pk>/edit/", views.edit_game_comment, name="edit_game_comment"),
    path("discuss/", views.board, name="board"),
    path("discuss/<int:pk>/edit/", views.edit_board_comment, name="edit_board_comment"),
    path("admin/users/", views.admin_users, name="admin_users"),
    path("admin/users/<int:pk>/edit/", views.edit_user, name="edit_user"),
    path("admin/users/<int:pk>/delete/", views.delete_user, name="delete_user"),
    path("admin/games/", views.admin_games, name="admin_games"),
    path("admin/games/<int:pk>/edit/", views.edit_game, name="edit_game"),
    path("admin/games/<int:pk>/delete/", views.delete_game, name="delete_game"),
]
