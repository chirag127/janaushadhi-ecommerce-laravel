@php $category = $category ?? null; @endphp
<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-input mt-1 text-sm" />
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description (optional)</label>
        <textarea id="description" name="description" rows="3" class="form-textarea mt-1 text-sm">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="image_url" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image URL (optional)</label>
        <input id="image_url" type="url" name="image_url" value="{{ old('image_url', $category->image_url ?? '') }}" class="form-input mt-1 text-sm" />
        @error('image_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sort order (optional)</label>
        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? '') }}" class="form-input mt-1 w-32 text-sm" />
        @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button type="submit" class="btn-primary text-sm">{{ $submitLabel ?? 'Save' }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn-secondary text-sm">Cancel</a>
</div>
