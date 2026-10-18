@extends('layouts.academie')
@section('title', 'Inscrire un enfant')

@section('content')
@php($season = config('academy.season'))
<div class="phone">
  <header class="topbar">
    <div class="row" style="justify-content:flex-start">
      <a class="back" href="{{ route('parent.dashboard') }}" aria-label="Retour" data-i18n-label="back">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
      </a>
      <h1 style="flex:1" data-i18n="enr_title">Inscrire un enfant</h1>
      <button class="btn-lang" id="langBtn" type="button">العربية</button>
    </div>
    <div class="steps" aria-hidden="true"><i class="on"></i><i class="on"></i><i></i></div>
    <span class="sub" data-i18n="enr_step">Étape 2 sur 3 · Choisir le groupe</span>
  </header>

  <form class="content" id="regForm" method="POST" action="{{ route('parent.registrations.store', $child) }}">
    @csrf
    @include('partials.flash')

    <div class="card kid" style="box-shadow:none;border:1px solid var(--line)">
      <div class="avatar sm h">{{ mb_substr($child->first_name, 0, 1) }}</div>
      <div class="info">
        <span class="name">{{ $child->full_name }}</span>
        <span class="meta">{{ $child->age }} ans</span>
      </div>
    </div>

    <h2 class="section-title" data-i18n="pick">Groupe d'entraînement</h2>

    @foreach ($groups as $g)
      @php($ok = $g->acceptsAge($child->age) && $g->reservedSeats($season) < $g->capacity)
      <label class="choice" @if (! $ok) style="opacity:.5" @endif>
        <input type="radio" name="training_group_id" value="{{ $g->id }}"
               @checked((int) old('training_group_id', $suggested?->id) === $g->id) @disabled(! $ok) required>
        <span class="t">
          <b>{{ $g->name }}</b>
          <span>{{ $g->schedule }} · <bdi>{{ number_format((float) $g->price, 3, ',', ' ') }} DT / mois</bdi>
            @if (! $g->acceptsAge($child->age)) · {{ $g->age_min }}-{{ $g->age_max }} ans @elseif (! $ok) · Complet @endif
          </span>
        </span>
      </label>
    @endforeach

    <p class="note" data-i18n="secure">Vous serez redirigé vers la page de paiement sécurisée de notre prestataire. L'académie ne conserve aucune donnée bancaire.</p>
  </form>

  <div class="footbar">
    <button class="btn btn-primary btn-block" type="submit" form="regForm" name="pay" value="online" data-i18n="pay_online">Payer en ligne</button>
    <button class="btn" type="submit" form="regForm" name="pay" value="later" style="min-height:44px;font-size:.9rem" data-i18n="pay_later">Payer plus tard ou sur place</button>
  </div>
</div>
@endsection
