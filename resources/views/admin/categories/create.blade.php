@extends('layouts.admin')

@section('title', 'New Category')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ route('admin.categories.index') }}" class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to categories</a>
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @include('admin.categories._form')
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create category</button>
            </div>
        </form>
    </div>
</div>
@endsection
