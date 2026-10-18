@extends('layouts.academie')
@section('title', 'Accueil')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row">
      <h1><span data-i18n="hello">Bonjour</span>, {{ auth()->user()->name }}</h1>
      @include('partials.topbtns')
    </div>
    <p data-i18n="hi_sub">Voici le suivi de vos enfants.</p>
  </header>

  <main class="content">
    @include('partials.flash')

    {{-- Paiements à régler --}}
    @foreach ($due as $payment)
      <div class="due">
        <div>
          <small data-i18n="due">Cotisation mensuelle à régler</small>
          <span style="font-size:.88rem">{{ $payment->registration->child->first_name }} · {{ $payment->billing_month ? \Carbon\Carbon::createFromFormat('!Y-m', $payment->billing_month)->format('m/Y') : $payment->created_at->format('m/Y') }}</span>
          <strong class="h" style="display:block"><bdi>{{ number_format((float) $payment->amount, 3, ',', ' ') }} DT</bdi></strong>
        </div>
        <a class="btn btn-primary" href="{{ route('parent.payments.checkout', $payment) }}" data-i18n="pay">{{ $payment->status === 'failed' ? 'Réessayer' : 'Payer' }}</a>
      </div>
    @endforeach

    <h2 class="section-title" data-i18n="my_kids">Mes enfants</h2>

    @forelse ($children as $child)
      @php($reg = $child->registrations->first())
      <article class="card kid">
        <div class="avatar h">{{ mb_substr($child->first_name, 0, 1) }}</div>
        <div class="info">
          <span class="name">{{ $child->full_name }}</span>
          @if ($reg)
            <span class="meta">{{ $reg->trainingGroup->name }} · {{ $reg->trainingGroup->schedule }}</span>
          @endif

          @if (! $reg)
            <span class="badge badge-off" style="align-self:flex-start;margin-top:4px" data-i18n="noreg">Pas encore inscrit</span>
            <a href="{{ route('parent.registrations.create', $child) }}" style="font-weight:800;font-size:.9rem" data-i18n="register_now">Inscrire</a>
          @elseif ($reg->status === 'approved')
            <span class="badge badge-ok" style="align-self:flex-start;margin-top:4px" data-i18n="k1_s">Inscription validée</span>
          @elseif ($reg->status === 'pending')
            <span class="badge badge-wait" style="align-self:flex-start;margin-top:4px" data-i18n="k2_s">En attente de validation</span>
          @else
            <span class="badge badge-off" style="align-self:flex-start;margin-top:4px" data-i18n="rej">Inscription refusée</span>
          @endif
        </div>
        <a href="{{ route('parent.children.edit', $child) }}" class="muted" aria-label="Modifier">✎</a>
      </article>
    @empty
      <p class="muted" data-i18n="no_kids">Aucun enfant pour le moment.</p>
    @endforelse

    <a class="add" href="{{ route('parent.children.create') }}">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
      <span data-i18n="add_kid">Inscrire un enfant</span>
    </a>

    {{-- Prochaine séance --}}
    @if ($nextSession)
      <section class="next">
        <small data-i18n="next">Prochaine séance</small>
        <div class="big h"><bdi>{{ $nextSession->starts_at->format('d/m/Y · H:i') }}</bdi></div>
        <div style="font-size:.9rem;color:#d6ebdd">{{ $nextSession->location ?? $nextSession->trainingGroup->location }} · {{ $nextSession->trainingGroup->name }}</div>
      </section>
    @endif

    {{-- Annonces --}}
    @foreach ($announcements as $a)
      <section class="card" style="border:1px solid var(--line);box-shadow:none">
        <small style="font-weight:800;color:var(--navy)">{{ $a->title }}</small>
        <p style="font-size:.9rem;color:#2b3f34">{{ $a->body }}</p>
      </section>
    @endforeach
  </main>

  <nav class="tabbar" aria-label="Navigation">
    <a href="{{ route('parent.dashboard') }}" aria-current="page">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
      <span data-i18n="nav_home">Accueil</span>
    </a>
    <a href="{{ route('parent.children.create') }}">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><circle cx="17.5" cy="9" r="2.5"/></svg>
      <span data-i18n="nav_kids">Enfants</span>
    </a>
    <a href="{{ route('profile.edit') }}">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
      <span data-i18n="nav_me">Profil</span>
    </a>
  </nav>
</div>
@endsection
