@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Us</h1>
    <p class="mt-1 text-sm text-gray-500">We're here to help. Send us a message or reach out directly.</p>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-1">
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Email</h2>
                <a href="mailto:{{ config('store.support_email') }}" class="text-sm text-brand-700 hover:underline">{{ config('store.support_email') }}</a>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Phone</h2>
                <a href="tel:{{ config('store.support_phone') }}" class="text-sm text-brand-700 hover:underline">{{ config('store.support_phone') }}</a>
            </div>
        </div>

        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('contact.submit') }}" class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255" class="form-input mt-1 text-sm" />
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" class="form-input mt-1 text-sm" />
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subject</label>
                    <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required maxlength="255" class="form-input mt-1 text-sm" />
                    @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="mt-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                    <textarea id="message" name="message" rows="5" required maxlength="5000" class="form-textarea mt-1 text-sm">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary mt-4 text-sm">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
