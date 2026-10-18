@extends('layouts.academie')
@section('title', 'Paiement')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row"><h1 data-i18n="pend_title">Paiement sur place ou par virement</h1><button class="btn-lang" id="langBtn" type="button">العربية</button></div>
  </header>
  <main class="content">
    <section class="card">
      <small class="muted" data-i18n="pend_amount">Cotisation du mois {{ $payment->billing_month ? \Carbon\Carbon::createFromFormat('!Y-m', $payment->billing_month)->format('m/Y') : $payment->created_at->format('m/Y') }}</small>
      <strong class="h" style="display:block;font-size:2rem;color:var(--navy)"><bdi>{{ number_format((float) $payment->amount, 3, ',', ' ') }} DT</bdi></strong>
      <p style="margin-top:10px" data-i18n="pend_text">Le paiement en ligne n'est pas encore activé. Réglez le montant à l'académie ou par virement : l'administrateur confirmera votre paiement.</p>
    </section>
    <a class="btn btn-primary btn-block" href="{{ route('parent.dashboard') }}" data-i18n="go_home">Retour à l'accueil</a>
  </main>
</div>
@endsection
