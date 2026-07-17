@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Privacy Policy</h1>
    <div class="prose prose-sm mt-4 max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
        <p>We collect only the information needed to process your orders: your name, contact details, and shipping address. We do not sell your personal data.</p>
        <h2 class="text-gray-900 dark:text-white">What we collect</h2>
        <p>Account details, order history, and delivery addresses. Payment is processed by our payment partner; we do not store card details.</p>
        <h2 class="text-gray-900 dark:text-white">How we use it</h2>
        <p>To fulfil orders, provide support, and improve our service. We may send transactional emails about your orders.</p>
        <h2 class="text-gray-900 dark:text-white">Contact</h2>
        <p>For privacy questions, email <a href="mailto:{{ config('store.support_email') }}">{{ config('store.support_email') }}</a>.</p>
    </div>
</div>
@endsection
