@extends('layouts.academie')
@section('title', 'Modifier la fiche')

@section('content')
<div class="phone">
  <header class="topbar">
    <div class="row" style="justify-content:flex-start">
      <a class="back" href="{{ route('parent.dashboard') }}" aria-label="Retour">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
      </a>
      <h1 style="flex:1" data-i18n="kid_edit">Modifier la fiche</h1>
      <button class="btn-lang" id="langBtn" type="button">العربية</button>
    </div>
  </header>

  @include('parent.children._form', ['action' => route('parent.children.update', $child), 'method' => 'PUT', 'child' => $child])
</div>
@endsection
