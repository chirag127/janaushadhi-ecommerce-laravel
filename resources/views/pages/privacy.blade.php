@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Privacy Policy</h1>
    <div class="prose prose-sm mt-6 max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
        <p>Your privacy matters to us. This policy explains what information we collect and how we use it.</p>
        <h2>Information we collect</h2>
        <p>We collect the information you provide when you create an account, place an order, or contact us: your name, email, phone number, and shipping address.</p>
        <h2>How we use it</h2>
        <p>We use your information solely to process orders, deliver products, and respond to your enquiries. We never sell your data to third parties.</p>
        <h2>Data security</h2>
        <p>We apply industry-standard safeguards to protect your personal information against unauthorised access.</p>
        <h2>Contact</h2>
        <p>Questions about this policy? Email us at <a href="mailto:{{ config('store.support_email') }}">{{ config('store.support_email') }}</a>.</p>
    </div>
</div>
@endsection
