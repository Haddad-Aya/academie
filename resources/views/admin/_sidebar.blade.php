<aside class="side">
  <a class="brand" href="/" style="color:inherit;text-decoration:none"><img class="brand-logo" src="{{ asset('images/logo.jpg') }}" alt=""><span class="h">{{ config('academy.name') }}</span></a>

  <a href="{{ route('admin.dashboard') }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>Tableau de bord</a>
  <a href="{{ route('admin.registrations.index') }}" @if (request()->routeIs('admin.registrations.*')) aria-current="page" @endif>Inscriptions</a>
  <a href="{{ route('admin.groups.index') }}" @if (request()->routeIs('admin.groups.*')) aria-current="page" @endif>Groupes</a>
  <a href="{{ route('admin.coaches.index') }}" @if (request()->routeIs('admin.coaches.*')) aria-current="page" @endif>Entraîneurs</a>
  <a href="{{ route('admin.gallery.index') }}" @if (request()->routeIs('admin.gallery.*')) aria-current="page" @endif>Galerie du site</a>
  <a href="{{ route('admin.payments.index') }}" @if (request()->routeIs('admin.payments.*')) aria-current="page" @endif>Paiements</a>
  <a href="{{ route('coach.groups') }}">Espace entraîneur</a>

  <form method="POST" action="{{ route('logout') }}" style="margin-top:auto">
    @csrf
    <button class="btn-lang" type="submit" style="width:100%">Déconnexion</button>
  </form>
</aside>
