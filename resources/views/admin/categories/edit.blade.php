@extends('layouts.admin')

@section('title', 'Edit Category')
@section('heading', 'Edit Category')

@section('admin')
    <form method="POST" action="{{ route('admin.categories.update', $category->slug) }}" class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')
        @include('admin.categories._form', ['category' => $category, 'submitLabel' => 'Update Category'])
    </form>
@endsection
