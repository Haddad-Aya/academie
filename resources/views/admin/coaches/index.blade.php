@extends('layouts.academie')
@section('title', 'Entraîneurs')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <h1 class="h">Entraîneurs</h1>

    @include('partials.flash')

    <section class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Nom</th><th>E-mail</th><th>Téléphone</th><th>Groupes</th><th>État</th><th></th></tr></thead>
          <tbody>
            @forelse ($coaches as $c)
              <tr>
                <td><strong>{{ $c->name }}</strong></td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->phone ?? '—' }}</td>
                <td>{{ $c->coached_groups_count }}</td>
                <td>@if ($c->active) <span class="badge badge-ok">Actif</span> @else <span class="badge badge-off">Désactivé</span> @endif</td>
                <td>
                  <form method="POST" action="{{ route('admin.coaches.toggle', $c) }}">@csrf
                    <button class="btn btn-outline btn-sm" type="submit">{{ $c->active ? 'Désactiver' : 'Réactiver' }}</button></form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="muted">Aucun entraîneur pour le moment.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="pager" style="margin-top:14px">{{ $coaches->links() }}</div>
    </section>

    <section class="card" style="max-width:760px">
      <h2 class="h section-title" style="margin:0 0 12px">Créer un compte entraîneur</h2>
      <form method="POST" action="{{ route('admin.coaches.store') }}" style="display:flex;flex-direction:column;gap:14px">
        @csrf
        <div class="form-grid">
          <div class="field"><label for="name">Nom complet</label><input id="name" name="name" value="{{ old('name') }}" required></div>
          <div class="field"><label for="email">E-mail</label><input id="email" name="email" type="email" class="ltr" value="{{ old('email') }}" required></div>
          <div class="field"><label for="phone">Téléphone</label><input id="phone" name="phone" class="ltr" value="{{ old('phone') }}"></div>
          <div class="field"><label for="password">Mot de passe (10 caractères minimum)</label><input id="password" name="password" type="password" class="ltr" required></div>
        </div>
        <div><button class="btn btn-primary" type="submit">Créer le compte</button></div>
      </form>
    </section>
  </main>
</div>
@endsection
