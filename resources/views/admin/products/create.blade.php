@extends('layouts.admin')

@section('title', 'New Product')
@section('heading', 'New Product')

@section('admin')
    <form method="POST" action="{{ route('admin.products.store') }}" class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @include('admin.products._form', ['product' => null, 'submitLabel' => 'Create Product'])
    </form>
@endsection
