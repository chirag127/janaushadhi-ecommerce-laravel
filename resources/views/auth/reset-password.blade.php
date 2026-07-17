@extends('layouts.app')

@section('title', 'Set new password')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12 sm:px-6">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Choose a new password</h1>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus autocomplete="email" class="form-input" />
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">New password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-input" />
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="form-input" />
            </div>
            <button type="submit" class="btn-primary w-full">Reset password</button>
        </form>
    </div>
</div>
@endsection
