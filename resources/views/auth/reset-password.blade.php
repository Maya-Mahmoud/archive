<x-guest-layout>
    <div class="auth-title">{{ __('New Password') }}</div>
    <div class="auth-subtitle">{{ __('Choose a new password for your account') }}</div>

    @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input class="auth-input" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">{{ __('Password') }}</label>
            <input class="auth-input" id="password" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input class="auth-input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="auth-btn">{{ __('Reset Password') }}</button>
    </form>
</x-guest-layout>
