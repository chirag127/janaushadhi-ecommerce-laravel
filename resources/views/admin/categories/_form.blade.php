{{-- Shared category form fields. Optional $category. --}}
@php $category = $category ?? null; @endphp
<div class="space-y-5">
    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}" required class="form-input" />
    </div>
    <div>
        <label for="description" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Description</label>
        <textarea id="description" name="description" rows="3" class="form-textarea">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div>
        <label for="image_url" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Image URL</label>
        <input id="image_url" name="image_url" type="url" value="{{ old('image_url', $category->image_url ?? '') }}" class="form-input" />
    </div>
    <div>
        <label for="sort_order" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Sort order</label>
        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="form-input" />
    </div>
</div>
