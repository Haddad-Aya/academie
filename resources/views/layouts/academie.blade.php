<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('academy.name'))</title>
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}?v=1">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=1">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
@yield('content')

{{-- Les écrans d'administration sont en français seulement : ils déclarent @section('plain') pour ne pas charger la bascule de langue. --}}
@hasSection('plain')
@else
<script src="{{ asset('js/i18n.js') }}"></script>
@endif
@stack('scripts')
</body>
</html>
