{{-- Shared product form fields. Expects $categories; optional $product. --}}
@php $product = $product ?? null; @endphp
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" required class="form-input" />
    </div>

    <div>
        <label for="drug_code" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Drug code</label>
        <input id="drug_code" name="drug_code" type="text" value="{{ old('drug_code', $product->drug_code ?? '') }}" class="form-input" />
    </div>

    <div>
        <label for="category_id" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Category</label>
        <select id="category_id" name="category_id" required class="form-select">
            <option value="">Select…</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="unit_size" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Unit size</label>
        <input id="unit_size" name="unit_size" type="text" value="{{ old('unit_size', $product->unit_size ?? '') }}" placeholder="e.g. 10 tablets" class="form-input" />
    </div>

    <div>
        <label for="image_url" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Image URL</label>
        <input id="image_url" name="image_url" type="url" value="{{ old('image_url', $product->image_url ?? '') }}" class="form-input" />
    </div>

    <div>
        <label for="mrp" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">MRP</label>
        <input id="mrp" name="mrp" type="number" step="0.01" min="0" value="{{ old('mrp', $product->mrp ?? '') }}" required class="form-input" />
    </div>

    <div>
        <label for="price" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Selling price</label>
        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required class="form-input" />
    </div>

    <div>
        <label for="stock" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Stock</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required class="form-input" />
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Description</label>
        <textarea id="description" name="description" rows="4" class="form-textarea">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
        <input type="hidden" name="is_active" value="0" />
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
        Active
    </label>

    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
        <input type="hidden" name="is_featured" value="0" />
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
        Featured
    </label>
</div>
