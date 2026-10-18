@extends('layouts.academie')
@section('title', 'Nouveau groupe')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')
  <main class="main">
    <h1 class="h">Nouveau groupe</h1>
    @include('partials.flash')
    @include('admin.groups._form', ['action' => route('admin.groups.store'), 'method' => null, 'group' => null])
  </main>
</div>
@endsection
