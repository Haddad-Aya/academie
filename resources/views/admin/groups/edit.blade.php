@extends('layouts.academie')
@section('title', 'Modifier le groupe')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')
  <main class="main">
    <h1 class="h">Modifier : {{ $group->name }}</h1>
    @include('partials.flash')
    @include('admin.groups._form', ['action' => route('admin.groups.update', $group), 'method' => 'PUT', 'group' => $group])
  </main>
</div>
@endsection
