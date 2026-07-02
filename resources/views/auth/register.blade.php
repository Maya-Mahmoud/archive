<x-guest-layout>
    <div class="auth-title">{{ $orgName ?? config('app.name') }}</div>
    <div class="auth-subtitle">{{ __('Create a new account') }}</div>

    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="name">{{ __('Name') }}</label>
            <input class="auth-input" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input class="auth-input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input class="auth-input" id="password" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input class="auth-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="auth-row">
            <a class="auth-link" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
        </div>

        <button type="submit" class="auth-btn">{{ __('Register') }}</button>
    </form>
</x-guest-layout>
