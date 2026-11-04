from django.urls import path

from . import views

app_name = "rhetorics"

urlpatterns = [
    path("", views.landing, name="landing"),
    path("crime-lab/", views.world1_home, name="world1_home"),
    path("crime-lab/research/", views.world1_research, name="world1_research"),
    path("crime-lab/crime-reduction/", views.world1_crime_reduction, name="world1_crime_reduction"),
    path("cra/", views.world2_home, name="world2_home"),
    path("cra/login/", views.login, name="login"),
    path("cra/logout/", views.logout, name="logout"),
    path("cra/signup/", views.signup, name="signup"),
    path("cra/forgot-password/", views.forgot_password, name="forgot_password"),
    path("cra/change-password/", views.change_password, name="change_password"),
    path("cra/news/", views.news, name="news"),
    path("cra/news/new/", views.news_new, name="news_new"),
    path("cra/news/<int:pk>/edit/", views.news_edit, name="news_edit"),
    path("cra/news/<int:pk>/delete/", views.news_delete, name="news_delete"),
    path("cra/ranges/", views.ranges, name="ranges"),
]
