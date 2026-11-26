@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <div class="auth-card">
        <div class="auth-icon"><i class="bi bi-intersect"></i></div>

        <div class="auth-eyebrow">Your space is ready</div>
        <h1 class="auth-title">Welcome back<span>.</span></h1>
        <p class="auth-sub">Sign in to return to the work that matters.</p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="auth-field">
                <div class="auth-label-row">
                    <label class="auth-label" for="email">Email address</label>
                </div>
                <input class="auth-input @error('email') has-error @enderror"
                       type="email" name="email" id="email"
                       value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email">
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <div class="auth-label-row">
                    <label class="auth-label" for="password">Password</label>
                </div>
                <div class="auth-input-wrap">
                    <input class="auth-input @error('password') has-error @enderror"
                           type="password" name="password" id="password"
                           placeholder="Enter your password" autocomplete="current-password">
                    <button type="button" class="auth-show" id="toggle-password">Show</button>
                </div>
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <label class="auth-remember">
                <input type="checkbox" name="remember" checked>
                Keep me signed in
            </label>

            <button type="submit" class="auth-submit">
                Continue to your space <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <p class="auth-switch">First time here? <a href="{{ route('register') }}">Create an account <i class="bi bi-arrow-up-right"></i></a></p>

    </div>
@endsection

@push('scripts')
<script>
    const toggle = document.getElementById('toggle-password');
    const password = document.getElementById('password');

    toggle.addEventListener('click', () => {
        const hidden = password.type === 'password';
        password.type = hidden ? 'text' : 'password';
        toggle.textContent = hidden ? 'Hide' : 'Show';
    });
</script>
@endpush