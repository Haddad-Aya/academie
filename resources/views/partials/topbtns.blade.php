{{-- Boutons du haut d'écran : langue + déconnexion --}}
<div class="lang-row">
  <button class="btn-lang" id="langBtn" type="button">العربية</button>
  <form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button class="btn-lang" type="submit" data-i18n="logout">Quitter</button>
  </form>
</div>
