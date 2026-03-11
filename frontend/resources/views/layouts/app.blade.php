<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITRA - Pelestarian Tari Topeng Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: url('https://i.imgur.com/0k5R9eG.jpg') repeat; background-size: 400px; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-indigo-900">
    <header class="bg-indigo-800 text-white p-4">
        <div class="container mx-auto flex items-center justify-between">
            <img src="https://ucic.ac.id/wp-content/uploads/2020/12/logo-ucic.png" alt="Logo UCIC" class="h-12">
            <h1 class="text-2xl font-bold">CITRA</h1>
            <nav class="flex space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('practice') }}" class="hover:underline">Latihan</a>
                    <a href="{{ route('leaderboard') }}" class="hover:underline">Leaderboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="hover:underline">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>