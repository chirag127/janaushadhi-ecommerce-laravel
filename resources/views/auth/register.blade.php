@extends('layouts.app')

@section('title', 'Sign Up')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create your account</h1>
        <p class="mt-1 text-sm text-gray-500">Join Jan Aushadhi for affordable medicines.</p>

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

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-input mt-1 text-sm" />
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-input mt-1 text-sm" />
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-input mt-1 text-sm" />
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-input mt-1 text-sm" />
            </div>
            <button type="submit" class="btn-primary w-full">Sign Up</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-brand-700 hover:underline">Log in</a>
        </p>
    </div>
</div>
@endsection
