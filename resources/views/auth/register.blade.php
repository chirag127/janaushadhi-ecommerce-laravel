@extends('layouts.app')

@section('title', 'Create account')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create your account</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Order affordable generic medicines.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-input" />
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="form-input" />
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-input" />
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="form-input" />
            </div>
            <button type="submit" class="btn-primary w-full">Create account</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Already have an account? <a href="{{ route('login') }}" wire:navigate class="font-medium text-brand-600 hover:underline">Log in</a>
        </p>
    </div>
</div>
@endsection
