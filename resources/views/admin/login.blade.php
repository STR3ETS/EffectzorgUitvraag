<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Effectzorg Uitvraag</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center antialiased">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
      <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-xl mx-auto flex items-center justify-center text-white font-bold text-2xl mb-4" style="background:linear-gradient(135deg,#0E94C4,#0a6480)">E</div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Login</h1>
        <p class="text-gray-500 mt-1 text-sm">Effectzorg Uitvraag Dashboard</p>
      </div>

      @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 mb-6 text-sm">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm">
        </div>
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord</label>
          <input type="password" name="password" required
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm">
        </div>
        <button type="submit"
          class="w-full py-3 px-4 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition text-sm">
          Inloggen
        </button>
      </form>
    </div>
    <p class="text-center text-xs text-gray-400 mt-4"><a href="/" class="hover:text-gray-600">&larr; Terug naar formulier</a></p>
  </div>
</body>
</html>
