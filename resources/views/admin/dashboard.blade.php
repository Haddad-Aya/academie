@extends('layouts.academie')
@section('title', 'Administration')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <div class="main-head">
      <div>
        <h1 class="h">Tableau de bord</h1>
        <span class="note">Saison {{ config('academy.season') }}</span>
      </div>
      <div class="actions">
        <a class="btn btn-outline" href="{{ route('admin.registrations.export') }}">Exporter vers Excel</a>
        <a class="btn btn-primary" href="{{ route('admin.groups.create') }}">Nouveau groupe</a>
      </div>
    </div>

    @include('partials.flash')

    <section class="kpis">
      <div class="card kpi"><b>{{ $stats['to_review'] }}</b><span>Inscriptions à valider</span></div>
      <div class="card kpi"><b>{{ $stats['active_players'] }}</b><span>Joueurs actifs</span></div>
      <div class="card kpi"><b>{{ $stats['pending_payments'] }}</b><span>Paiements en attente</span></div>
      <div class="card kpi"><b>{{ $stats['attendance_rate'] === null ? '—' : $stats['attendance_rate'].' %' }}</b><span>Présence moyenne</span></div>
    </section>

    <section class="card">
      <h2 class="h section-title" style="margin:0 0 12px">Inscriptions à valider</h2>
      @include('admin.registrations._table', ['rows' => $pending])
    </section>

    <section class="card">
      <h2 class="h section-title" style="margin:0 0 12px">Groupes et entraîneurs</h2>
      <div class="cards">
        @foreach ($groups as $g)
          <div class="group">
            <div class="cat">{{ $g->category }}</div>
            <b>{{ $g->coach?->name ?? 'Sans entraîneur' }}</b>
            <div class="note">{{ $g->schedule }} · {{ $g->capacity }} places</div>
          </div>
        @endforeach
      </div>
    </section>
  </main>
</div>
@endsection
