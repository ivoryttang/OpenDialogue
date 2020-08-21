from django.db import models

from django.contrib.auth.models import UserManager
from django.conf import settings

import re
from django.contrib.auth.models import PermissionsMixin
from django.contrib.auth.base_user import AbstractBaseUser

from django.contrib.auth.base_user import BaseUserManager

class UserManager(BaseUserManager):
    use_in_migrations = True

    def _create_user(self, username, email, password, **extra_fields):
        """
        Creates and saves a User with the given password.
        """
        user = self.model(username=username, email=email, **extra_fields)
        user.set_password(password)
        user.is_active = True
        user.is_admin = True
        user.is_staff = True
        user.save(using=self._db)
        return user

    def create_user(self, username, email, password, **extra_fields):
        extra_fields.setdefault('is_superuser', False)
        return self._create_user(username, email, password, **extra_fields)

    def create_superuser(self, username, email, password, **extra_fields):
        extra_fields.setdefault('is_superuser', True)

        if extra_fields.get('is_superuser') is not True:
            raise ValueError('Superuser must have is_superuser=True.')

        return self._create_user(username, email, password, **extra_fields)

class UserInfo(AbstractBaseUser, PermissionsMixin):
    REQUIRED_FIELDS = []
    USERNAME_FIELD = 'username'
    is_anonymous = False
    is_authenticated = True
    id = models.AutoField(null=False, primary_key=True)
    is_staff = models.BooleanField(('staff status'),default=True)
    is_active = models.BooleanField(('active status'),default=True)
    is_superuser = models.BooleanField(('user status'),default=False)
    last_login = models.CharField(max_length=60, unique=True, default="")
    user = models.OneToOneField(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, default="")
    username = models.CharField(max_length=60, unique=True, default="")
    name = models.CharField(max_length=60, unique=True, default="")
    password = models.CharField(max_length=60, unique=False, default="")
    email = models.EmailField(max_length=60, unique=True, default="")
    photo = models.ImageField(upload_to=None, height_field=None, width_field=None, max_length=100, default="https://m2bob-forum.net/wcf/images/avatars/3e/2720-3e546be0b0701e0cb670fa2f4fcb053d4f7e1ba5.jpg")
    phone = models.CharField(max_length=20, unique=False, default="")
    profession = models.CharField(max_length=30, unique=False, default="")
    intro = models.CharField(max_length=100, unique=False, default="")
    objects = UserManager()

    def __str__(self):
        return self.username

    def email_user(self, subject, message, from_email=None, **kwargs):
        '''
        Sends an email to this User.
        '''
        #send_mail(subject, message, from_email, [self.email], **kwargs)


class Tweet(models.Model):
    topic_ref = models.ForeignKey(
        'Topics',
        on_delete=models.CASCADE, default="1"
    )
    topic = models.CharField(max_length=100, unique=False, default="unknown")
    username = models.CharField(max_length=255, unique=False, default="unknown")
    created_at = models.CharField(max_length=45, unique=False, default="1/1/2020")
    text = models.TextField()
    likes = models.IntegerField(default=0, null=True)
    reply = models.TextField(max_length=1000, unique=False, default="")
    polarity = models.IntegerField(default=0, null=True)
    subjectivity = models.IntegerField(default=0, null=True)

    def __str__(self):
        return self.text

class Topics(models.Model):
    topic = models.CharField(max_length=100, unique=True, default="unknown")
    def __str__(self):
        return self.topic

