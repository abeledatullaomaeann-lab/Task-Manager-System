<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Log in') | {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;1,6..72,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="rings">
        <span class="ring r1"></span>
        <span class="ring r2"></span>
        <span class="ring r3"></span>
    </div>

    <header class="auth-header">
        <div class="auth-brand">
            <span class="auth-brand-mark">tm</span>
            Task Manager
        </div>
        <a href="#" class="auth-help">Need a hand?</a>
    </header>

    <main class="auth-main">
        @yield('content')
    </main>

    <footer class="auth-footer">
        <span>&copy; {{ date('Y') }} Task Manager</span>
        <span>Built with Laravel</span>
    </footer>

    @stack('scripts')
</body>
</html>