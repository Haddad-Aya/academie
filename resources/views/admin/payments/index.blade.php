@extends('layouts.academie')
@section('title', 'Paiements')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <h1 class="h">Paiements</h1>

    <p><a class="btn btn-outline btn-sm" href="{{ route('admin.payments.statistics') }}">Voir les statistiques mensuelles</a></p>

    @include('partials.flash')

    <nav class="filters" aria-label="Filtrer par statut">
      <a href="{{ route('admin.payments.index') }}" @if (! $status) aria-current="true" @endif>Tous</a>
      <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" @if ($status === 'pending') aria-current="true" @endif>En attente</a>
      <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" @if ($status === 'paid') aria-current="true" @endif>Payés</a>
      <a href="{{ route('admin.payments.index', ['status' => 'failed']) }}" @if ($status === 'failed') aria-current="true" @endif>Échecs</a>
    </nav>

    <section class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Mois dû</th><th>Enfant</th><th>Parent</th><th>Groupe</th><th>Montant</th><th>Méthode</th><th>Statut</th><th></th></tr></thead>
          <tbody>
            @forelse ($payments as $p)
              <tr>
                <td>{{ $p->billing_month ? \Carbon\Carbon::createFromFormat('!Y-m', $p->billing_month)->format('m/Y') : $p->created_at->format('m/Y') }}</td>
                <td><strong>{{ $p->registration->child->full_name }}</strong></td>
                <td>
                  {{ $p->registration->child->parent->name }}<br>
                  <span class="muted">
                    @if ($p->registration->child->parent->phone)
                      <a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->registration->child->parent->phone) }}">{{ $p->registration->child->parent->phone }}</a><br>
                    @endif
                    <a href="mailto:{{ $p->registration->child->parent->email }}">{{ $p->registration->child->parent->email }}</a>
                  </span>
                </td>
                <td>{{ $p->registration->trainingGroup->name }}</td>
                <td>{{ number_format((float) $p->amount, 3, ',', ' ') }} {{ $p->currency }}</td>
                <td>
                  @if ($p->payment_method === 'onsite') Sur place
                  @elseif ($p->payment_method === 'card') Historique
                  @else Non précisée @endif
                </td>
                <td>
                  @if ($p->status === 'paid') <span class="badge badge-ok">Payé</span>
                  @elseif ($p->status === 'failed') <span class="badge badge-off">Échec</span>
                  @else <span class="badge badge-wait">En attente</span> @endif
                </td>
                <td>
                  @if ($p->status !== 'paid')
                    <form method="POST" action="{{ route('admin.payments.paid', $p) }}"
                          onsubmit="return confirm('Confirmer que ce paiement a bien été reçu ?')">@csrf
                      <button class="btn btn-primary btn-sm" type="submit">Paiement reçu</button></form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="muted">Aucun paiement.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="pager" style="margin-top:14px">{{ $payments->links() }}</div>
    </section>
  </main>
</div>
@endsection
