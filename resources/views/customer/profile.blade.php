@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile</h1>

    {{-- Details --}}
    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Account details</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-4 space-y-5">
            @csrf @method('PATCH')
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="form-input" />
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="form-input" />
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Phone</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="form-input" />
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Save changes</button>
            </div>
        </form>
    </div>

    {{-- Password --}}
    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Change password</h2>
        <form method="POST" action="{{ route('profile.password') }}" class="mt-4 space-y-5">
            @csrf @method('PUT')
            <div>
                <label for="current_password" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Current password</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="form-input" />
            </div>
            <div>
                <label for="new_password" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">New password</label>
                <input id="new_password" name="password" type="password" autocomplete="new-password" class="form-input" />
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-input" />
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Update password</button>
            </div>
        </form>
    </div>
</div>
@endsection
