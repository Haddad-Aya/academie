@extends('layouts.academie')
@section('title', 'Mes groupes')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row">
      <h1 data-i18n="cg_title">Mes groupes</h1>
      @include('partials.topbtns')
    </div>
    <span class="sub">{{ auth()->user()->name }}</span>
  </header>

  <main class="content">
    @include('partials.flash')

    @forelse ($groups as $g)
      <section class="card" style="display:flex;flex-direction:column;gap:10px">
        <div>
          <h2 class="h" style="color:var(--navy)">{{ $g->name }}</h2>
          <p class="muted">{{ $g->schedule }} · {{ $g->location }}</p>
        </div>

        @forelse ($g->sessions as $s)
          <a class="btn btn-outline btn-block" href="{{ route('coach.attendance.show', $s) }}">
            <bdi>{{ $s->starts_at->format('d/m/Y H:i') }}</bdi> · <span data-i18n="open_call">Faire l'appel</span>
          </a>
        @empty
          <p class="muted" data-i18n="no_sessions">Aucune séance prévue.</p>
        @endforelse

        <form method="POST" action="{{ route('coach.sessions.store', $g) }}" style="display:flex;flex-direction:column;gap:8px;border-top:1px dashed var(--line);padding-top:10px">
          @csrf
          <div class="field">
            <label for="dt{{ $g->id }}" data-i18n="date_time">Date et heure</label>
            <input id="dt{{ $g->id }}" type="datetime-local" name="starts_at" class="ltr" required>
          </div>
          <button class="btn btn-primary btn-sm" type="submit" data-i18n="add_session">Ajouter une séance</button>
        </form>
      </section>
    @empty
      <p class="muted" data-i18n="no_groups">Aucun groupe ne vous est attribué.</p>
    @endforelse
  </main>

  <nav class="tabbar" aria-label="Navigation">
    <a href="{{ route('coach.groups') }}" aria-current="page">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><circle cx="17.5" cy="9" r="2.5"/></svg>
      <span data-i18n="nav_groups">Mes groupes</span>
    </a>
  </nav>
</div>
@endsection
