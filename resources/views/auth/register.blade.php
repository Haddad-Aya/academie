@extends('layouts.academie')
@section('title', 'Créer un compte')

@section('content')
<div class="phone">
  <header class="topbar tall">
    <div class="row">
      <a class="brand" href="/" style="color:inherit;text-decoration:none"><img class="brand-logo" src="{{ asset('images/logo.jpg') }}" alt=""><span class="h" data-i18n="brand">Academie Future Football Djerba</span></a>
      <button class="btn-lang" id="langBtn" type="button">العربية</button>
    </div>
    <h1 data-i18n="tab_signup">Créer un compte</h1>
    <p data-i18n="login_sub">Suivez vos enfants, inscrivez-les et payez en ligne.</p>
  </header>

  <main class="card card-float">
    <form method="POST" action="{{ route('register') }}" style="display:contents">
      @csrf
      <div class="field">
        <label for="name" data-i18n="f_name">Nom complet</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
        @error('name') <span class="err">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="email" data-i18n="f_email_req">E-mail</label>
        <input id="email" name="email" type="email" class="ltr" value="{{ old('email') }}" autocomplete="username" required>
        @error('email') <span class="err">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="phone" data-i18n="f_phone_req">Téléphone du parent</label>
        <input id="phone" name="phone" type="tel" class="ltr" value="{{ old('phone') }}" autocomplete="tel" placeholder="+216 12 345 678" required>
        @error('phone') <span class="err">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="password" data-i18n="f_pass">Mot de passe</label>
        <input id="password" name="password" type="password" class="ltr" autocomplete="new-password" required>
        @error('password') <span class="err">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="password_confirmation" data-i18n="f_pass2">Confirmer le mot de passe</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="ltr" autocomplete="new-password" required>
      </div>
      <button class="btn btn-primary btn-block" type="submit" data-i18n="btn_signup">Créer mon compte</button>
    </form>

    <p class="auth-switch"><span data-i18n="have_account">Déjà un compte ?</span>
      <a href="{{ route('login') }}" style="font-weight:800" data-i18n="tab_login">Connexion</a></p>
  </main>
</div>
@endsection
