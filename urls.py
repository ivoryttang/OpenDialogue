"""od URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/3.0/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path
from . import views
from django.views.generic.base import TemplateView


from . import views

urlpatterns = [
    path('accounts/signup/', views.SignUp.as_view(), name='signup'),
    #path('', TemplateView.as_view(template_name='home.html'), name='home'),
    path('od/home', views.home, name='home'),
    path('od/logout', views.logout, name='logout'),
    path('od/profile', views.profile, name='profile'),
    path('od/edit_profile', views.edit_profile, name='edit_profile'),
    #path('accounts/password_reset/', views.password_reset_form, name='password_reset_form'),
    #path('accounts/password_reset/done/', views.password_reset_done, name='password_reset_done'),
    #path('accounts/reset/uid/token/', views.password_reset_confirm, name='password_reset_confirm'),
    #path('accounts/password_reset_form/', views.password_reset_form, name='password_reset_form'),
    #path('accounts/reset/done/', views.password_reset_complete, name='password_reset_complete'),
    path('od/race', views.race, name='race'),
    path('od/gender', views.gender, name='gender'),
    path('od/sexual_orientation', views.sexual_orientation, name='sexual_orientation'),
    path('od/register', views.register, name='register'),
    path('od/dashboard', views.dashboard, name='dashboard'),
    path('od/logged_in', views.logged_in, name='logged_in'),
    path('view/', views.view_others, name='view'),
    path('od/top10', views.top10),
    path('od/blm.php', views.blm),
    path('od/new_topic.php', views.new_topic),
    path('od/where_to_post', views.where_to_post, name='where_to_post'),
    path('od/search_results', views.search_results, name='search_results'),

]

