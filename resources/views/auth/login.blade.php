@extends('layouts.academie')
@section('title', 'Connexion')

@section('content')
<div class="phone">
  <header class="topbar tall">
    <div class="row">
      <a class="brand" href="/" style="color:inherit;text-decoration:none"><img class="brand-logo" src="{{ asset('images/logo.jpg') }}" alt=""><span class="h" data-i18n="brand">Academie Future Football Djerba</span></a>
      <button class="btn-lang" id="langBtn" type="button">العربية</button>
    </div>
    <h1 data-i18n="login_space">Espace parents</h1>
    <p data-i18n="login_sub">Suivez vos enfants, inscrivez-les et payez en ligne.</p>
  </header>

  <main class="card card-float">
    @if (session('status'))
      <div class="flash flash-ok" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" style="display:contents">
      @csrf
      <div class="field">
        <label for="email" data-i18n="f_email_req">E-mail</label>
        <input id="email" name="email" type="email" class="ltr" value="{{ old('email') }}" autocomplete="username" required autofocus>
        @error('email') <span class="err">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="password" data-i18n="f_pass">Mot de passe</label>
        <input id="password" name="password" type="password" class="ltr" autocomplete="current-password" required>
        @error('password') <span class="err">{{ $message }}</span> @enderror
      </div>
      <label class="check"><input type="checkbox" name="remember"><span data-i18n="f_remember">Se souvenir de moi</span></label>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" style="font-weight:700;font-size:.9rem" data-i18n="forgot">Mot de passe oublié ?</a>
      @endif
      <button class="btn btn-primary btn-block" type="submit" data-i18n="btn_login">Se connecter</button>
    </form>

    <p class="auth-switch"><span data-i18n="no_account">Pas encore de compte ?</span>
      <a href="{{ route('register') }}" style="font-weight:800" data-i18n="tab_signup">Créer un compte</a></p>
  </main>

  <footer class="row" style="padding:22px 24px;font-size:.9rem;color:#3d5045">
    <span data-i18n="help">Besoin d'aide ?</span>
    <a href="https://wa.me/21600000000" style="font-weight:800;min-height:44px;display:inline-flex;align-items:center" data-i18n="wa">Écrire sur WhatsApp</a>
  </footer>
</div>
@endsection
