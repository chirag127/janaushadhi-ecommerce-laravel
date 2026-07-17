@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">About {{ config('app.name') }}</h1>
    <div class="prose prose-sm mt-4 max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
        <p>{{ config('app.name') }} makes quality generic medicines and healthcare products affordable and accessible across India. Generic medicines contain the same active ingredients as their branded counterparts at a fraction of the cost.</p>
        <p>We list thousands of products across dozens of therapeutic categories, with transparent pricing and fast delivery. Our goal is simple: lower the cost of staying healthy.</p>
        <h2 class="text-gray-900 dark:text-white">Why generic?</h2>
        <p>Generic medicines are approved to be as safe and effective as brand-name drugs. Choosing generics can reduce your medicine bill significantly without compromising on quality.</p>
    </div>
</div>
@endsection
