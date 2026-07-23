@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset your password</h1>
        <p class="mt-1 text-sm text-gray-500">Choose a new password for your account.</p>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus autocomplete="email" class="form-input mt-1 text-sm" />
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">New password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-input mt-1 text-sm" />
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-input mt-1 text-sm" />
            </div>
            <button type="submit" class="btn-primary w-full">Reset Password</button>
        </form>
    </div>
</div>
@endsection
