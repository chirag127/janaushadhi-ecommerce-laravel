@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ open: 0 }">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Frequently asked questions</h1>
    <div class="mt-6 divide-y divide-gray-100 rounded-xl border border-gray-100 bg-white dark:divide-gray-800 dark:border-gray-800 dark:bg-gray-900">
        @php
            $faqs = [
                ['Are generic medicines safe?', 'Yes. Generic medicines contain the same active ingredients as branded drugs and meet the same regulatory standards for safety and efficacy.'],
                ['How long does delivery take?', 'Most orders are delivered within 3–7 business days depending on your location.'],
                ['What payment methods do you accept?', 'We accept Cash on Delivery and online payment via card, UPI, and netbanking.'],
                ['Can I return an order?', 'Medicines cannot be returned once delivered for safety reasons. Contact support for damaged or incorrect items.'],
                ['Do I need a prescription?', 'Some products require a valid prescription. Please consult your physician before ordering prescription medicines.'],
            ];
        @endphp
        @foreach ($faqs as $i => $faq)
            <div>
                <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="flex w-full items-center justify-between px-5 py-4 text-left">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $faq[0] }}</span>
                    <span class="text-gray-400" x-text="open === {{ $i }} ? '−' : '+'"></span>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-4 text-sm text-gray-600 dark:text-gray-300">{{ $faq[1] }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
