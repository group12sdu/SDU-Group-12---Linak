<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Desk Manager') - UniDesk</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
</head>
<body>
    <div id="notification-area" aria-label="Notifications">
        @if (session('toast'))
            @include('partials.toast', ['type' => session('toast')[0], 'message' => session('toast')[1]])
        @endif
        @foreach ($errors->all() as $error)
            @include('partials.toast', ['type' => 'error', 'message' => $error])
        @endforeach
    </div>

    <header id="app-header">
        <h1>UniDesk <span class="brand-dot">.</span></h1>

        @auth
            <nav aria-label="Main navigation">
                <a href="{{ route('my-desk') }}" @class(['is-active' => request()->routeIs('my-desk')])>My desk</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.desks.index') }}" @class(['is-active' => request()->routeIs('admin.desks.*')])>Manage desks</a>
                    <a href="{{ route('admin.employees.index') }}" @class(['is-active' => request()->routeIs('admin.employees.*')])>Manage employees</a>
                    <a href="{{ route('admin.cleaning.index') }}" @class(['is-active' => request()->routeIs('admin.cleaning.*')])>Cleaning</a>
                @endif
            </nav>
            <div id="user-box">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="logout-link">Log out</button>
                </form>
            </div>
        @endauth
    </header>

    <main>
        @yield('content')
    </main>

    <footer>Desk Manager demo</footer>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
