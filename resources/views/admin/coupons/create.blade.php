@extends('layouts.admin')

@section('title', 'New Coupon')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ route('admin.coupons.index') }}" class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to coupons</a>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            @include('admin.coupons._form')
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection
