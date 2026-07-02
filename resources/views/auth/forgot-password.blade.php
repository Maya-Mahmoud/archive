<x-guest-layout>
    <div class="auth-title">{{ __('Reset Password') }}</div>
    <div class="auth-subtitle">{{ __('We will email you a reset link') }}</div>

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

    <p style="color:#64748b;font-size:.88rem;margin-bottom:1.25rem;line-height:1.6">
        {{ __('Forgot your password? No problem. Enter your email address and we will send you a link to choose a new one.') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="email">{{ __('Email') }}</label>
            <input class="auth-input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="auth-btn">{{ __('Email Password Reset Link') }}</button>
    </form>
</x-guest-layout>
