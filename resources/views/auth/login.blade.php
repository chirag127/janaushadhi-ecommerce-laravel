@extends('layouts.app')

@section('title', 'Log In')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h1>
        <p class="mt-1 text-sm text-gray-500">Log in to your Jan Aushadhi account.</p>

        @if (session('status'))
            <p class="mt-4 rounded-lg bg-brand-50 px-3 py-2 text-sm text-brand-700">{{ session('status') }}</p>
        @endif

        <div class="mt-6 grid grid-cols-2 gap-2">
            <a href="{{ route('social.redirect', 'google') }}" class="btn-secondary">Google</a>
            <a href="{{ route('social.redirect', 'github') }}" class="btn-secondary">GitHub</a>
            <a href="{{ route('social.redirect', 'microsoft') }}" class="btn-secondary">Microsoft</a>
            <a href="{{ route('social.redirect', 'facebook') }}" class="btn-secondary">Facebook</a>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
            <span class="text-xs uppercase tracking-wide text-gray-400">or</span>
            <span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-input mt-1 text-sm" />
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                    <a href="{{ route('password.request') }}" wire:navigate class="text-xs text-brand-700 hover:underline">Forgot password?</a>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-input mt-1 text-sm" />
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500" />
                <span class="text-sm text-gray-600 dark:text-gray-300">Remember me</span>
            </label>
            <button type="submit" class="btn-primary w-full">Log In</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" wire:navigate class="font-medium text-brand-700 hover:underline">Sign up</a>
        </p>
    </div>
</div>
@endsection
