@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-brand-600">Admin</p>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('heading')</h1>
        </div>
        @hasSection('actions')
            <div class="flex items-center gap-2">@yield('actions')</div>
        @endif
    </div>

    <div class="flex flex-col gap-6 lg:flex-row">
        @include('partials.admin-nav')
        <div class="min-w-0 flex-1">
            @yield('admin')
        </div>
    </div>
</div>
@endsection
