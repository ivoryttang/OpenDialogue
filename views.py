# Create your views here.
from django.contrib.auth import authenticate, login, logout
from django.http import HttpResponse, HttpResponseRedirect
from django.shortcuts import render, redirect
from django.urls import reverse
from od.models import UserInfo, Tweet, Topics
import logging
from django import forms
from django.contrib.auth.models import User
from .models import UserInfo, UserManager
from django.urls import reverse
# users/forms.py

from django.contrib.auth.forms import UserCreationForm

class CustomUserCreationForm(UserCreationForm):
    class Meta(UserCreationForm.Meta):
        fields = UserCreationForm.Meta.fields + ("email",)
#create views here.

from django.contrib.auth.forms import UserCreationForm
from django.urls import reverse_lazy
from django.views import generic


class SignUp(generic.CreateView):
    form_class = UserCreationForm
    success_url = reverse_lazy('login')
    template_name = 'registration/register.html'

#def password_change(request):
 #   return render(request, "change_password.html", {"message": None})

def password_reset_email(request):
    return render(request, "registration/password_reset_email.html", {"message": None})

def password_reset_done(request):
    return render(request, "registration/password_reset_done.html", {"message": None})

def password_reset_form(request):
    return render(request, "registration/password_reset_form.html", {"message": None})

def password_reset_complete(request):
    return render(request, "registration/password_reset_complete.html", {"message": None})

def password_reset_confirm(request):
    return render(request, "registration/password_reset_confirm.html", {"message": None})

def logout(request):
    try:
        del request.session['username']
    except:
        pass
    return render(request, "logout.html", {"message": None})

#checks create account parameters
def home(request):
    go_home = True
    try:
        # Create user and save to the database
        p = request.POST.get("password")
        def hasNumbers(inputString):
            return any(char.isdigit() for char in inputString)
        def hasLetters(inputString):
            return any(char.isalpha() for char in inputString) and any(char.isupper() for char in inputString)
        if len(p) >= 8 and p == request.POST.get("confirm_password") and hasNumbers(p) and hasLetters(p):
            user = UserInfo.objects.create_user(username=request.POST.get("username"), email=request.POST.get("email"), password=request.POST.get("password"));
            user.save();
    except:
        go_home=False
        pass
    if not go_home:
        return render(request, "registration/register.html", context={"failed_to_create_account":"Unable to create account."})
    else:
        return render(request, "home.html", {"message": None})

#authentification
def dashboard(request):
    try:
        username = request.POST['username']
        password = request.POST['password']
        user = authenticate(request, username=username, password=password)
        if user is not None:
            request.session['username'] = username
            request.session['password'] = password
            context = {
                "username" : username,
                "password" : password
            }
            return render(request, "dashboard.html", context)
        else:
            context = {
                "failed_to_log_in" : "Invalid username or password. Please try again."
            }
            return render(request, "registration/login.html", context)
    except:
        context = {
            "failed_to_log_in" : "Invalid username or password. Please try again."
        }
        return render(request, "registration/login.html", context)

def logged_in(request):
    context = {
        "username" : request.session['username'],
    }
    return render(request, "dashboard.html", context)

def register(request):
    if request.method == "GET":
        return render(
            request, "registration/register.html",
            {"form": CustomUserCreationForm}
        )
    elif request.method == "POST":
        form = CustomUserCreationForm(request.POST)
        if form.is_valid():
            user = form.save()
            login(user)
            return redirect(reverse("dashboard"))

def view_others(request):
    return render(request, "view_others.html", {"message": None})

def top10(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "top10.html", context)

def blm(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "blm.php", context)

def new_topic(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "new_topic.php", context)

def where_to_post(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
            "username" : "unknown"
        }
    return render(request, "where_to_post.html", context)

def search_results(request):
    context = {
        "username" : request.session['username']
    }
    return render(request, "search_results.html", context)

def race(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "race.php", context)

def gender(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "gender.html", context)

def sexual_orientation(request):
    try:
        context = {
            "username" : request.session['username']
        }
    except:
        context = {
        }
    return render(request, "sexual_orientation.html", context)

def profile(request):
    try:
        request.session['intro'] = request.POST['intro']
        request.session['name'] = request.POST['name']
        request.session['photo'] = request.POST['photo']
        request.session['email'] = request.POST['email']
        request.session['phone'] =request.POST['phone']
        request.session['profession'] =request.POST['profession']

        import mysql.connector

        mydb = mysql.connector.connect(
            host="localhost",
            user="postgres",
            password="postgres",
            database="postgres"
        )

        mycursor = mydb.cursor()

        mycursor.execute("UPDATE od_userinfo SET name=%s WHERE username=%s", (request.session['name'], "Ivory"))

        context = {
            "username" : request.session['username'],
            "photo": request.session['photo'],
            "intro" : request.session['intro'],
            "name" : request.session['name'],
            "email" : request.session['email'],
            "phone" : request.session['phone'],
            "profession" : request.session['profession']
        }
    except:
        context = {
            "username" : request.session['username']
        }
    return render(request, "user/profile.html", context)

def edit_profile(request):
    try:
        #request.session['username'] = request.POST['username']
        #request.session['photo'] = request.POST['photo']
        context = {
            "username" : request.session['username'],
            "intro" : request.session['intro'],
            "name" : request.session['name'],
            "email" : request.session['email'],
            "phone" : request.session['phone'],
            "profession" : request.session['profession']
        }
    except:
        context = {
            "username" : request.session['username']
        }
    return render(request, "user/edit_profile.html", context)