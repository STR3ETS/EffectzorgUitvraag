<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin — Effectzorg Uitvraag')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <div class="flex items-center gap-4">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-bold text-lg" style="background:linear-gradient(135deg,#0E94C4,#0a6480)">E</div>
          <a href="{{ route('admin.dashboard') }}" class="font-semibold text-lg text-gray-900 hover:text-blue-600 transition">Admin Dashboard</a>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">Uitloggen</button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
  </main>
</body>
</html>
