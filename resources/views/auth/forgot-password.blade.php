@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset your password</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter your email and we'll send a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-input" />
            </div>
            <button type="submit" class="btn-primary w-full">Send reset link</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-brand-600 hover:underline">Back to log in</a>
        </p>
    </div>
</div>
@endsection
