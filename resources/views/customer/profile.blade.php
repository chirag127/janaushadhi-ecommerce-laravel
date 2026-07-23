@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>

    {{-- Profile details --}}
    <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input mt-1 text-sm" />
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1 text-sm" />
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone (optional)</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1 text-sm" />
                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary text-sm">Save Changes</button>
        </form>
    </section>

    {{-- Password --}}
    <section class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h2>
        <form method="POST" action="{{ route('profile.password') }}" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Current password</label>
                <input id="current_password" type="password" name="current_password" autocomplete="current-password" class="form-input mt-1 text-sm" />
                @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">New password</label>
                <input id="password" type="password" name="password" autocomplete="new-password" class="form-input mt-1 text-sm" />
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm new password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="form-input mt-1 text-sm" />
            </div>
            <button type="submit" class="btn-primary text-sm">Update Password</button>
        </form>
    </section>
</div>
@endsection
