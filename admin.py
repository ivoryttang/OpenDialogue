# Register your models here.
from django.contrib import admin

from .models import UserInfo, Tweet, Topics

admin.site.register(UserInfo)
admin.site.register(Tweet)
admin.site.register(Topics)