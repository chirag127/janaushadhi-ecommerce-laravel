@extends('layouts.admin')

@section('title', 'New Category')
@section('heading', 'New Category')

@section('admin')
    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @include('admin.categories._form', ['category' => null, 'submitLabel' => 'Create Category'])
    </form>
@endsection
