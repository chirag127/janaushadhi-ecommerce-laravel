@php $coupon = $coupon ?? null; @endphp
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code</label>
        <input id="code" type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" class="form-input mt-1 text-sm uppercase" />
        @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type</label>
        <select id="type" name="type" class="form-select mt-1 text-sm">
            <option value="percent" @selected(old('type', $coupon->type ?? '') === 'percent')>Percentage (%)</option>
            <option value="fixed" @selected(old('type', $coupon->type ?? '') === 'fixed')>Fixed amount (₹)</option>
        </select>
        @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Value</label>
        <input id="value" type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" min="0" class="form-input mt-1 text-sm" />
        @error('value')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="min_order_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Min order amount (₹)</label>
        <input id="min_order_amount" type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}" min="0" class="form-input mt-1 text-sm" />
        @error('min_order_amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="max_discount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Max discount (₹, optional)</label>
        <input id="max_discount" type="number" step="0.01" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" min="0" class="form-input mt-1 text-sm" />
        @error('max_discount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="usage_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Usage limit (optional)</label>
        <input id="usage_limit" type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" min="1" class="form-input mt-1 text-sm" />
        @error('usage_limit')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="starts_at" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Starts at (optional)</label>
        <input id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-input mt-1 text-sm" />
        @error('starts_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Expires at (optional)</label>
        <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-input mt-1 text-sm" />
        @error('expires_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <label class="flex items-center gap-2 sm:col-span-2">
        <input type="hidden" name="is_active" value="0" />
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true)) class="rounded text-brand-600 focus:ring-brand-500" />
        <span class="text-sm text-gray-700 dark:text-gray-200">Active</span>
    </label>
</div>

<div class="mt-6 flex gap-2">
    <button type="submit" class="btn-primary text-sm">{{ $submitLabel ?? 'Save' }}</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn-secondary text-sm">Cancel</a>
</div>
