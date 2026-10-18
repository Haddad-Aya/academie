@extends('layouts.academie')
@section('title', 'Groupes')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <div class="main-head">
      <h1 class="h">Groupes</h1>
      <a class="btn btn-primary" href="{{ route('admin.groups.create') }}">Nouveau groupe</a>
    </div>

    @include('partials.flash')

    <section class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Groupe</th><th>Âges</th><th>Horaires</th><th>Entraîneur</th><th>Places</th><th>Cotisation mensuelle</th><th>État</th><th></th></tr></thead>
          <tbody>
            @foreach ($groups as $g)
              <tr>
                <td><strong>{{ $g->name }}</strong></td>
                <td>{{ $g->age_min }}–{{ $g->age_max }} ans</td>
                <td>{{ $g->schedule ?? '—' }}</td>
                <td>{{ $g->coach?->name ?? '—' }}</td>
                <td>{{ $g->capacity }}</td>
                <td>{{ number_format((float) $g->price, 3, ',', ' ') }} DT / mois</td>
                <td>@if ($g->active) <span class="badge badge-ok">Actif</span> @else <span class="badge badge-off">Inactif</span> @endif</td>
                <td><a class="btn btn-outline btn-sm" href="{{ route('admin.groups.edit', $g) }}">Modifier</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
@endsection
