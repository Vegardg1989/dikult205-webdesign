from django.urls import include, path
from django.views.generic import RedirectView

urlpatterns = [
    path("", RedirectView.as_view(url="/demo/rhetorics/")),
    path("demo/retrobaser/", include("retrobaser.urls")),
    path("demo/rhetorics/", include("rhetorics.urls")),
]
