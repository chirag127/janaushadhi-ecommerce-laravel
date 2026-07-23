@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8" x-data="{ open: 0 }">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h1>
    <div class="mt-6 space-y-3">
        @foreach ([
            ['Are generic medicines safe?', 'Yes. Generic medicines contain the same active ingredients and meet the same safety, quality, and efficacy standards as branded drugs. All our products come from WHO-GMP certified manufacturers.'],
            ['How much can I save?', 'Generics typically cost up to 90% less than branded equivalents while delivering the same therapeutic effect.'],
            ['What are the delivery charges?', 'Delivery is free on orders above ₹' . number_format((float) config('store.free_shipping_threshold')) . '. A flat fee of ₹' . number_format((float) config('store.shipping_fee')) . ' applies to smaller orders.'],
            ['What payment methods do you accept?', 'We accept Cash on Delivery and online payment via UPI and cards.'],
            ['Can I cancel my order?', 'Orders can be cancelled while they are still pending or confirmed, from your order details page.'],
        ] as $i => [$q, $a])
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="flex w-full items-center justify-between px-5 py-4 text-left">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $q }}</span>
                    <svg class="h-5 w-5 text-gray-400 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-4 text-sm text-gray-600 dark:text-gray-300">{{ $a }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
