@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Log in to your account.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-input" />
            </div>
            <div>
                <div class="mb-1 flex items-center justify-between">
                    <label for="password" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Password</label>
                    <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-brand-600 hover:underline">Forgot?</a>
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="form-input" />
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
                Remember me
            </label>
            <button type="submit" class="btn-primary w-full">Log in</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            No account? <a href="{{ route('register') }}" wire:navigate class="font-medium text-brand-600 hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection
