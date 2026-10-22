@extends('layouts.academie')
@section('title', 'Feuille de présence')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row">
      <div>
        <h1 data-i18n="att_title">Feuille de présence</h1>
        <span class="sub">{{ $session->trainingGroup->name }}</span>
      </div>
      <div class="counter h" aria-live="polite"><b id="count">0/0</b><span data-i18n="here">présents</span></div>
    </div>
    <div class="row">
      <a class="btn-lang" href="{{ route('coach.groups') }}" style="text-decoration:none;display:inline-flex;align-items:center" data-i18n="back">Retour</a>
      <button class="btn-lang" id="langBtn" type="button">العربية</button>
    </div>
  </header>

  <form class="content" id="attForm" method="POST" action="{{ route('coach.attendance.store', $session) }}">
    @csrf
    @include('partials.flash')

    <b style="color:var(--navy);font-size:.9rem">
      <bdi>{{ $session->starts_at->format('d/m/Y H:i') }}</bdi> · {{ $session->location ?? $session->trainingGroup->location }}
    </b>
    <span class="note" data-i18n="att_hint">Touchez un joueur pour changer son statut.</span>

    {{-- Le coach voit uniquement les coordonnées du parent des enfants de son groupe. Les notes médicales restent privées. --}}
    @forelse ($players as $child)
      <div style="display:grid;gap:4px;margin-bottom:8px">
        <label class="player">
          <input type="checkbox" name="present[]" value="{{ $child->id }}" @checked(in_array($child->id, $present, true))>
          <span class="avatar sm h">{{ mb_substr($child->first_name, 0, 1) }}</span>
          <span class="n">{{ $child->first_name }} {{ mb_substr($child->last_name, 0, 1) }}.</span>
          <span class="state"><span class="on" data-i18n="present">Présent</span><span class="off" data-i18n="absent">Absent</span></span>
        </label>
        @if ($child->parent)
          <div class="muted" style="padding:0 12px 4px 54px;font-size:.82rem;line-height:1.7">
            <strong>{{ $child->parent->name }}</strong><br>
            @if ($child->parent->phone)
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $child->parent->phone) }}">{{ $child->parent->phone }}</a>
            @endif
            @if ($child->parent->phone && $child->parent->email) · @endif
            @if ($child->parent->email)
              <a href="mailto:{{ $child->parent->email }}">{{ $child->parent->email }}</a>
            @endif
          </div>
        @endif
      </div>
    @empty
      <p class="note">Aucun enfant n’a une inscription validée pour ce groupe et la saison {{ config('academy.season') }}. L’appel ne peut pas être enregistré.</p>
    @endforelse
  </form>

  <div class="footbar">
    <button class="btn btn-primary btn-block" type="submit" form="attForm" @disabled($players->isEmpty()) data-i18n="save_att">Enregistrer l'appel</button>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Compteur de présents mis à jour à chaque case cochée.
  var boxes = document.querySelectorAll('input[name="present[]"]');
  function updateCount() {
    var c = 0;
    boxes.forEach(function (b) { if (b.checked) c++; });
    document.getElementById('count').textContent = c + '/' + boxes.length;
  }
  boxes.forEach(function (b) { b.addEventListener('change', updateCount); });
  updateCount();
</script>
@endpush
