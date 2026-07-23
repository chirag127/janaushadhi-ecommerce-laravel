@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Forgot your password?</h1>
        <p class="mt-1 text-sm text-gray-500">Enter your email and we'll send a reset link.</p>

        @if (session('status'))
            <p class="mt-4 rounded-lg bg-brand-50 px-3 py-2 text-sm text-brand-700">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-input mt-1 text-sm" />
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary w-full">Send Reset Link</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-brand-700 hover:underline">Back to login</a>
        </p>
    </div>
</div>
@endsection
