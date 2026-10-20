@extends('layouts.academie')
@section('title', 'Statut du paiement')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row"><h1 data-i18n="ret_title">Statut du paiement</h1><button class="btn-lang" id="langBtn" type="button">العربية</button></div>
  </header>
  <main class="content">
    <section class="card">
      {{-- Le statut est synchronisé depuis l'API Konnect au retour, ou via le webhook. --}}
      @if ($payment->status === 'paid')
        <p class="flash flash-ok" data-i18n="st_paid">Paiement reçu. Merci !</p>
      @elseif ($payment->status === 'failed')
        <p class="flash flash-err" data-i18n="st_failed">Le paiement a échoué.</p>
        <a class="btn btn-primary btn-block" style="margin-top:12px" href="{{ route('parent.payments.checkout', $payment) }}" data-i18n="retry">Réessayer</a>
      @else
        <p class="flash" style="background:#fff0c2;color:#5a4300" data-i18n="st_pending">Paiement en attente de confirmation.</p>
      @endif
    </section>
    <a class="btn btn-outline btn-block" href="{{ route('parent.dashboard') }}" data-i18n="go_home">Retour à l'accueil</a>
  </main>
</div>
@endsection
