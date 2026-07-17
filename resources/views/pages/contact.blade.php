@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact us</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Questions about an order or a product? Send us a message.</p>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name ?? '') }}" required class="form-input" />
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="form-input" />
                    </div>
                </div>
                <div>
                    <label for="subject" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Subject</label>
                    <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required class="form-input" />
                </div>
                <div>
                    <label for="message" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Message</label>
                    <textarea id="message" name="message" rows="5" required class="form-textarea">{{ old('message') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Send message</button>
                </div>
            </form>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Support</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Email: <a href="mailto:{{ config('store.support_email') }}" class="text-brand-600 hover:underline">{{ config('store.support_email') }}</a></p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Phone: {{ config('store.support_phone') }}</p>
            </div>
        </aside>
    </div>
</div>
@endsection
