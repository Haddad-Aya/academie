@extends('layouts.academie')
@section('title', 'Inscriptions')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <div class="main-head">
      <h1 class="h">Inscriptions</h1>
      <a class="btn btn-outline" href="{{ route('admin.registrations.export') }}">Exporter vers Excel</a>
    </div>

    @include('partials.flash')

    <nav class="filters" aria-label="Filtrer par statut">
      <a href="{{ route('admin.registrations.index') }}" @if (! $status) aria-current="true" @endif>Toutes</a>
      <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" @if ($status === 'pending') aria-current="true" @endif>À valider</a>
      <a href="{{ route('admin.registrations.index', ['status' => 'approved']) }}" @if ($status === 'approved') aria-current="true" @endif>Validées</a>
      <a href="{{ route('admin.registrations.index', ['status' => 'rejected']) }}" @if ($status === 'rejected') aria-current="true" @endif>Refusées</a>
    </nav>

    <section class="card">
      @include('admin.registrations._table', ['rows' => $registrations])
      <div class="pager" style="margin-top:14px">{{ $registrations->links() }}</div>
    </section>
  </main>
</div>
@endsection
