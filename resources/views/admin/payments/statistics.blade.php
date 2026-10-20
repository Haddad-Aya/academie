@extends('layouts.academie')
@section('title', 'Statistiques des paiements')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <h1 class="h">Statistiques des paiements</h1>
    <p class="muted">Les montants sont regroupés par mois de cotisation. Un paiement n'est compté comme encaissé qu'après confirmation.</p>

    <form method="GET" action="{{ route('admin.payments.statistics') }}" class="filters" style="margin:18px 0">
      <label for="year">Année</label>
      <select id="year" name="year" style="max-width:150px;height:40px;padding:0 12px;border:2px solid var(--line);border-radius:10px;background:#fff">
        @foreach (range(now()->year + 1, now()->year - 4) as $optionYear)
          <option value="{{ $optionYear }}" @selected($year === $optionYear)>{{ $optionYear }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary btn-sm" type="submit">Afficher</button>
      <a class="btn btn-outline btn-sm" href="{{ route('admin.payments.index') }}">Retour aux paiements</a>
    </form>

    <section class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Mois de cotisation</th><th>Total attendu</th><th>Encaissé</th><th>À régler</th><th>Carte</th><th>Sur place</th><th>Payés</th><th>En attente</th><th>Échoués</th></tr>
          </thead>
          <tbody>
            @foreach ($months as $month)
              <tr>
                <td><strong>{{ $month['label'] }}</strong></td>
                <td>{{ number_format($month['expected'], 3, ',', ' ') }} TND</td>
                <td><strong>{{ number_format($month['collected'], 3, ',', ' ') }} TND</strong></td>
                <td>{{ number_format(max(0, $month['expected'] - $month['collected']), 3, ',', ' ') }} TND</td>
                <td>{{ number_format($month['card'], 3, ',', ' ') }} TND</td>
                <td>{{ number_format($month['onsite'], 3, ',', ' ') }} TND</td>
                <td>{{ $month['paid_count'] }}</td>
                <td>{{ $month['pending_count'] }}</td>
                <td>{{ $month['failed_count'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
@endsection
