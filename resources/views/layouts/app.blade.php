<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Effectzorg — Uitvraag software & werkprocessen')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Figtree:wght@400;500;600;700&family=Spline+Sans+Mono:wght@500&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('styles')
</head>
<body>
@yield('content')
@stack('scripts')
</body>
</html>
