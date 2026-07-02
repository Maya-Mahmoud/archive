<header class="app-header">
    <a href="{{ route('dashboard') }}" class="app-header__brand">{{ $orgName ?? config('app.name') }}</a>

    <div class="app-header__actions">
        @if (Auth::user()->hasPermission('settings.manage'))
            <a href="{{ route('settings.index') }}" class="header-btn header-btn--icon" title="{{ __('Settings') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="header-btn header-btn--profile" title="{{ __('Profile') }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>{{ Auth::user()->name }}</span>
        </a>

      <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="header-btn header-btn--icon" title="{{ __('Log Out') }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 3v9M18.36 6.64a9 9 0 11-12.72 0"/>
        </svg>
    </button>
</form>
    </div>
</header>

<nav class="app-nav">
    <div class="app-nav__inner">
        <a href="{{ route('dashboard') }}" class="app-nav__link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            {{ __('Dashboard') }}
        </a>
        @if (Auth::user()->hasPermission('documents.view'))
            <a href="{{ route('documents.index') }}" class="app-nav__link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                {{ __('Documents') }}
            </a>
        @endif
        @if (Auth::user()->hasPermission('users.manage'))
            <a href="{{ route('users.index') }}" class="app-nav__link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                {{ __('Users') }}
            </a>
        @endif
        @if (Auth::user()->hasPermission('roles.manage'))
            <a href="{{ route('roles.index') }}" class="app-nav__link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                {{ __('Roles') }}
            </a>
        @endif
    </div>
</nav>
