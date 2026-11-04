from django.urls import path

from . import views

app_name = "rhetorics"

urlpatterns = [
    path("", views.landing, name="landing"),
    path("crime-lab/", views.world1_home, name="world1_home"),
    path("crime-lab/research/", views.world1_research, name="world1_research"),
    path("cra/", views.world2_home, name="world2_home"),
    path("cra/login/", views.login, name="login"),
    path("cra/logout/", views.logout, name="logout"),
    path("cra/news/", views.news, name="news"),
    path("cra/news/new/", views.news_new, name="news_new"),
    path("cra/news/<int:pk>/edit/", views.news_edit, name="news_edit"),
    path("cra/news/<int:pk>/delete/", views.news_delete, name="news_delete"),
    path("cra/ranges/", views.ranges, name="ranges"),
]
