@extends('layouts.admin')

@section('title', 'Categories')
@section('heading', 'Categories')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn-primary text-sm">Add Category</a>
@endsection

@section('admin')
    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3 text-right">Products</th>
                    <th class="px-4 py-3 text-right">Sort</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $category->imageOrPlaceholder() }}" alt="" class="h-10 w-10 rounded object-cover" />
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ $category->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-right">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2 text-xs">
                                <a href="{{ route('admin.categories.edit', $category->slug) }}" class="text-brand-700 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->slug) }}" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
