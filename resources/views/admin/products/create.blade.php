@extends('layouts.admin')

@section('title', 'New Product')

@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.products.index') }}" class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to products</a>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            @include('admin.products._form', ['categories' => $categories])
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create product</button>
            </div>
        </form>
    </div>
</div>
@endsection
