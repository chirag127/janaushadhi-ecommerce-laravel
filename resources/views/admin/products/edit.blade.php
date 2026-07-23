@extends('layouts.admin')

@section('title', 'Edit Product')
@section('heading', 'Edit Product')

@section('actions')
    <a href="{{ route('admin.products.show', $product->slug) }}" class="btn-secondary text-sm">View</a>
@endsection

@section('admin')
    <form method="POST" action="{{ route('admin.products.update', $product->slug) }}" class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')
        @include('admin.products._form', ['product' => $product, 'submitLabel' => 'Update Product'])
    </form>
@endsection
