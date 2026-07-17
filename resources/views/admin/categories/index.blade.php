@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $categories->count() }} categories</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">New category</a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Products</th>
                    <th class="px-4 py-3">Sort</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->products_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
