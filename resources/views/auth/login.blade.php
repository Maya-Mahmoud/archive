<x-guest-layout>
    <div class="auth-title">{{ $orgName ?? config('app.name') }}</div>
    <div class="auth-subtitle">{{ __('Sign in to your account') }}</div>

    @if (session('status'))
        <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input class="auth-input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="enter your email">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input class="auth-input" id="password" type="password" name="password" required autocomplete="current-password" placeholder="enter your password">
        </div>

        <div class="auth-row">
            <label class="auth-remember">
                <input  type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            @endif
        </div>

        <button type="submit" class="auth-btn">{{ __('Log in') }}</button>
    </form>
</x-guest-layout>
