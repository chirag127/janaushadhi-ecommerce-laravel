@extends('layouts.admin')

@section('title', 'New Coupon')
@section('heading', 'New Coupon')

@section('admin')
    <form method="POST" action="{{ route('admin.coupons.store') }}" class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @include('admin.coupons._form', ['coupon' => null, 'submitLabel' => 'Create Coupon'])
    </form>
@endsection
