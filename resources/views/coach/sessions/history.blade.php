@extends('layouts.academie')
@section('title', 'Séances et historique')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row">
      <div>
        <h1 data-i18n="session_history">Séances et historique</h1>
        <span class="sub" data-i18n="session_history_sub">Retrouvez les séances et les appels par période.</span>
      </div>
      @include('partials.topbtns')
    </div>
    <a class="btn-lang" href="{{ route('coach.groups') }}" style="text-decoration:none;display:inline-flex;align-items:center;margin-top:10px" data-i18n="back">Retour</a>
  </header>

  <main class="content">
    <form method="GET" action="{{ route('coach.sessions.history') }}" class="card" style="display:grid;gap:12px;margin-bottom:16px">
      <div class="form-grid">
        <div class="field">
          <label for="year" data-i18n="history_year">Année</label>
          <select id="year" name="year">
            @foreach (range(now()->year + 1, 2020) as $optionYear)
              <option value="{{ $optionYear }}" @selected($year === $optionYear)>{{ $optionYear }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label for="month" data-i18n="history_month">Mois</label>
          <select id="month" name="month">
            <option value="" data-i18n="history_all_months">Toute l’année</option>
            @foreach ([1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'] as $monthNumber => $monthName)
              <option value="{{ $monthNumber }}" @selected($month === $monthNumber)>{{ $monthName }}</option>
            @endforeach
          </select>
        </div>
        @if ($groups->isNotEmpty())
          <div class="field">
            <label for="group_id" data-i18n="history_group">Groupe</label>
            <select id="group_id" name="group_id">
              <option value="" data-i18n="history_all_groups">Tous les groupes</option>
              @foreach ($groups as $group)
                <option value="{{ $group->id }}" @selected($groupId === $group->id)>{{ $group->name }}</option>
              @endforeach
            </select>
          </div>
        @endif
      </div>
      <button class="btn btn-primary btn-sm" type="submit" data-i18n="history_filter">Filtrer les séances</button>
    </form>

    @forelse ($sessions as $session)
      <section class="card" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px">
        <div class="row" style="justify-content:space-between;gap:12px">
          <strong>{{ $session->trainingGroup->name }}</strong>
          <span class="badge {{ $session->attendances_count > 0 ? 'badge-ok' : 'badge-wait' }}">
            <span data-i18n="{{ $session->attendances_count > 0 ? 'history_call_done' : 'history_call_todo' }}">{{ $session->attendances_count > 0 ? 'Appel fait' : 'Appel à faire' }}</span>
          </span>
        </div>
        <span><bdi>{{ $session->starts_at->format('d/m/Y à H:i') }}</bdi></span>
        <span class="muted" @if (! $session->location) data-i18n="history_no_location" @endif>{{ $session->location ?: 'Lieu non précisé' }}</span>
        @if ($session->attendances_count > 0)
          <span class="muted">{{ $session->present_count }} présent(s) sur {{ $session->attendances_count }} enfant(s) enregistré(s)</span>
        @endif
        <a class="btn btn-outline btn-sm" href="{{ route('coach.attendance.show', $session) }}">
          <span data-i18n="{{ $session->attendances_count > 0 ? 'history_call_edit' : 'open_call' }}">{{ $session->attendances_count > 0 ? 'Consulter ou modifier l’appel' : 'Faire l’appel' }}</span>
        </a>
      </section>
    @empty
      <p class="card muted" data-i18n="history_empty">Aucune séance trouvée pour cette période.</p>
    @endforelse

    @if ($sessions->hasPages())
      <div class="card" style="margin-top:12px">{{ $sessions->links() }}</div>
    @endif
  </main>
</div>
@endsection
