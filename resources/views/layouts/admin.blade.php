<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — {{ config('app.name') }} Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-gray-100 font-sans text-gray-800 antialiased dark:bg-gray-950 dark:text-gray-100">
<div x-data="{ sidebar: false }" class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 shrink-0 transform border-r border-gray-200 bg-white transition-transform dark:border-gray-800 dark:bg-gray-900 lg:static lg:translate-x-0"
        :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
        aria-label="Admin navigation"
    >
        <div class="flex h-16 items-center gap-2 border-b border-gray-200 px-5 dark:border-gray-800">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5v15m0 0h1.5m-1.5 0h-1.5m-3-15h9a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0116.5 19.5h-9A2.25 2.25 0 015.25 17.25V6.75A2.25 2.25 0 017.5 4.5z" /></svg>
            </span>
            <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </div>
        <nav class="space-y-1 px-3 py-4 text-sm">
            @php
                $link = 'flex items-center gap-3 rounded-lg px-3 py-2 font-medium transition';
                $active = 'bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300';
                $idle = 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800';
            @endphp
            <a href="{{ route('admin.dashboard') }}" class="{{ $link }} {{ request()->routeIs('admin.dashboard') ? $active : $idle }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="{{ $link }} {{ request()->routeIs('admin.products.*') ? $active : $idle }}">Products</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ $link }} {{ request()->routeIs('admin.categories.*') ? $active : $idle }}">Categories</a>
            <a href="{{ route('admin.inventory.index') }}" class="{{ $link }} {{ request()->routeIs('admin.inventory.*') ? $active : $idle }}">Inventory</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ $link }} {{ request()->routeIs('admin.orders.*') ? $active : $idle }}">Orders</a>
            <a href="{{ route('admin.customers.index') }}" class="{{ $link }} {{ request()->routeIs('admin.customers.*') ? $active : $idle }}">Customers</a>
            <a href="{{ route('admin.coupons.index') }}" class="{{ $link }} {{ request()->routeIs('admin.coupons.*') ? $active : $idle }}">Coupons</a>
            <a href="{{ route('admin.reports.index') }}" class="{{ $link }} {{ request()->routeIs('admin.reports.*') ? $active : $idle }}">Reports</a>
            <div class="my-3 border-t border-gray-200 dark:border-gray-800"></div>
            <a href="{{ route('home') }}" class="{{ $link }} {{ $idle }}">View store</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="{{ $link }} w-full text-left text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">Log out</button>
            </form>
        </nav>
    </aside>

    {{-- Backdrop (mobile) --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    {{-- Main --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-gray-200 bg-white/95 px-4 backdrop-blur dark:border-gray-800 dark:bg-gray-900/95 sm:px-6">
            <button type="button" @click="sidebar = !sidebar" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 lg:hidden" aria-label="Toggle menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            <h1 class="truncate text-lg font-semibold text-gray-900 dark:text-white">@yield('heading', View::yieldContent('title'))</h1>
            <div class="ml-auto flex items-center gap-3">
                <button type="button" onclick="window.__toggleTheme && window.__toggleTheme()" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Toggle dark mode">
                    <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                    <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                </button>
                <span class="hidden text-sm text-gray-600 dark:text-gray-300 sm:inline">{{ auth()->user()->name }}</span>
            </div>
        </header>

        @include('partials.flash')

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>
