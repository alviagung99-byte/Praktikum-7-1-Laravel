<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SmartEduInclusion</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    @stack('styles')
</head>
<body class="dashboard-body">

<div class="dashboard-wrapper">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <span class="logo-icon">🎓</span>
                <div>
                    <strong>SmartEdu</strong>
                    <small>@yield('sidebar-subtitle', 'Dashboard')</small>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar-menu')
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" style="width:100%; border:none; background:none; text-align:left; cursor:pointer;">🚪 Keluar</button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">☰</button>
            <h1 class="topbar-title">@yield('page-title')</h1>
            <div class="topbar-user">
                <span class="user-avatar">@yield('user-initials', 'U')</span>
            </div>
        </header>

        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="/js/app.js"></script>
@stack('scripts')
</body>
</html>
