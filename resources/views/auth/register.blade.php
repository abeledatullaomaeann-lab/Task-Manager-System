@extends('layouts.guest')

@section('title', 'Create account')

@section('content')
    <div class="auth-card compact">
        <div class="auth-icon"><i class="bi bi-person-plus"></i></div>

        <div class="auth-eyebrow">Let's get you set up</div>
        <h1 class="auth-title">Create account<span>.</span></h1>
        <p class="auth-sub">Start organizing your tasks in a minute.</p>

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf

            <div class="auth-field">
                <div class="auth-label-row">
                    <label class="auth-label" for="name">Full name</label>
                </div>
                <input class="auth-input @error('name') has-error @enderror"
                       type="text" name="name" id="name"
                       value="{{ old('name') }}" placeholder="Your name" autocomplete="name">
                @error('name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

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
                           placeholder="At least 8 characters" autocomplete="new-password">
                    <button type="button" class="auth-show" id="toggle-password">Show</button>
                </div>
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <div class="auth-label-row">
                    <label class="auth-label" for="password_confirmation">Confirm password</label>
                </div>
                <input class="auth-input"
                       type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="Type it again" autocomplete="new-password">
            </div>

            <button type="submit" class="auth-submit">
                Create my account <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <p class="auth-switch">Already have an account? <a href="{{ route('login') }}">Sign in <i class="bi bi-arrow-up-right"></i></a></p>
    </div>
@endsection

@push('scripts')
<script>
    const toggle = document.getElementById('toggle-password');
    const fields = [document.getElementById('password'), document.getElementById('password_confirmation')];

    toggle.addEventListener('click', () => {
        const hidden = fields[0].type === 'password';
        fields.forEach((field) => field.type = hidden ? 'text' : 'password');
        toggle.textContent = hidden ? 'Hide' : 'Show';
    });
</script>
@endpush