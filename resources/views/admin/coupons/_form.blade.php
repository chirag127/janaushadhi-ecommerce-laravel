{{-- Shared coupon form fields. Optional $coupon. --}}
@php
    $coupon = $coupon ?? null;
    $fmt = fn ($dt) => $dt ? \Illuminate\Support\Carbon::parse($dt)->format('Y-m-d\TH:i') : '';
@endphp
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="code" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Code</label>
        <input id="code" name="code" type="text" value="{{ old('code', $coupon->code ?? '') }}" required class="form-input font-mono uppercase" />
    </div>
    <div>
        <label for="type" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Type</label>
        <select id="type" name="type" required class="form-select">
            <option value="percent" @selected(old('type', $coupon->type ?? '') === 'percent')>Percentage</option>
            <option value="fixed" @selected(old('type', $coupon->type ?? '') === 'fixed')>Fixed amount</option>
        </select>
    </div>
    <div>
        <label for="value" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Value</label>
        <input id="value" name="value" type="number" step="0.01" min="0" value="{{ old('value', $coupon->value ?? '') }}" required class="form-input" />
    </div>
    <div>
        <label for="min_order_amount" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Min order amount</label>
        <input id="min_order_amount" name="min_order_amount" type="number" step="0.01" min="0" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}" class="form-input" />
    </div>
    <div>
        <label for="max_discount" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Max discount</label>
        <input id="max_discount" name="max_discount" type="number" step="0.01" min="0" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" class="form-input" />
    </div>
    <div>
        <label for="usage_limit" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Usage limit</label>
        <input id="usage_limit" name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" class="form-input" />
    </div>
    <div>
        <label for="starts_at" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Starts at</label>
        <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $fmt($coupon->starts_at ?? null)) }}" class="form-input" />
    </div>
    <div>
        <label for="expires_at" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Expires at</label>
        <input id="expires_at" name="expires_at" type="datetime-local" value="{{ old('expires_at', $fmt($coupon->expires_at ?? null)) }}" class="form-input" />
    </div>
    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 sm:col-span-2">
        <input type="hidden" name="is_active" value="0" />
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true)) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
        Active
    </label>
</div>
