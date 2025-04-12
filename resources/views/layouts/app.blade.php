<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-4">
        <nav class="flex justify-between mb-6">
            <div><a href="{{ route('dashboard') }}" class="font-bold text-lg ">Task Manager</a></div>
            <div>
                @auth
                    <span class="mr-2">Hi, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </nav>

        @if (session('success'))
            <div class="bg-green-100 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
