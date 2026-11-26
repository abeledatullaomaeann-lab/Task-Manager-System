<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager') | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;1,6..72,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
   <link href="{{ asset('css/style.css') }}" rel="stylesheet">
   <link href="{{ asset('css/rose.css') }}?v={{ filemtime(public_path('css/rose.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="app">
        <x-sidebar />

        <main class="main">
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>