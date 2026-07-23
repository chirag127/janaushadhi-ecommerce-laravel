@php $product = $product ?? null; @endphp
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-input mt-1 text-sm" />
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category</label>
        <select id="category_id" name="category_id" class="form-select mt-1 text-sm">
            <option value="">Select category</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="drug_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Drug code (optional)</label>
        <input id="drug_code" type="text" name="drug_code" value="{{ old('drug_code', $product->drug_code ?? '') }}" class="form-input mt-1 text-sm" />
        @error('drug_code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="unit_size" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Unit size (optional)</label>
        <input id="unit_size" type="text" name="unit_size" value="{{ old('unit_size', $product->unit_size ?? '') }}" placeholder="e.g. 10 tablets" class="form-input mt-1 text-sm" />
        @error('unit_size')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Stock</label>
        <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" class="form-input mt-1 text-sm" />
        @error('stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="mrp" class="block text-sm font-medium text-gray-700 dark:text-gray-200">MRP (₹)</label>
        <input id="mrp" type="number" step="0.01" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" min="0" class="form-input mt-1 text-sm" />
        @error('mrp')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Selling price (₹)</label>
        <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" class="form-input mt-1 text-sm" />
        @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="image_url" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image URL (optional)</label>
        <input id="image_url" type="url" name="image_url" value="{{ old('image_url', $product->image_url ?? '') }}" class="form-input mt-1 text-sm" />
        @error('image_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description (optional)</label>
        <textarea id="description" name="description" rows="4" class="form-textarea mt-1 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-6 sm:col-span-2">
        <label class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0" />
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded text-brand-600 focus:ring-brand-500" />
            <span class="text-sm text-gray-700 dark:text-gray-200">Active</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="hidden" name="is_featured" value="0" />
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="rounded text-brand-600 focus:ring-brand-500" />
            <span class="text-sm text-gray-700 dark:text-gray-200">Featured</span>
        </label>
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button type="submit" class="btn-primary text-sm">{{ $submitLabel ?? 'Save' }}</button>
    <a href="{{ route('admin.products.index') }}" class="btn-secondary text-sm">Cancel</a>
</div>
