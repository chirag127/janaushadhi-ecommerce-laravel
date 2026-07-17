@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Terms of Service</h1>
    <div class="prose prose-sm mt-4 max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
        <p>By using {{ config('app.name') }}, you agree to these terms. Please read them carefully.</p>
        <h2 class="text-gray-900 dark:text-white">Orders</h2>
        <p>All orders are subject to product availability and confirmation. Prices are shown in {{ config('store.currency') }} and may change without notice.</p>
        <h2 class="text-gray-900 dark:text-white">Medicines</h2>
        <p>Some products require a valid prescription. You are responsible for consulting a qualified physician before use.</p>
        <h2 class="text-gray-900 dark:text-white">Liability</h2>
        <p>We are not liable for misuse of products purchased through the platform. Always follow dosage guidance from your healthcare provider.</p>
    </div>
</div>
@endsection
