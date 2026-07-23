@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ showForm: {{ $addresses->isEmpty() ? 'true' : 'false' }}, edit: null }">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Addresses</h1>
        <button type="button" @click="showForm = true; edit = null" class="btn-primary text-sm">Add Address</button>
    </div>

    {{-- Address list --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        @foreach ($addresses as $address)
            <div class="rounded-xl border {{ $address->is_default ? 'border-brand-400 ring-1 ring-brand-200' : 'border-gray-100' }} bg-white p-5 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $address->label }}</span>
                    @if ($address->is_default)
                        <span class="badge bg-brand-100 text-brand-800">Default</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $address->full_name }}</p>
                <p class="text-sm text-gray-500">{{ $address->singleLine() }}, {{ $address->country }}</p>
                <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                    @unless ($address->is_default)
                        <form method="POST" action="{{ route('addresses.default', $address->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded border border-gray-200 px-3 py-1 text-gray-600 hover:bg-gray-50">Make default</button>
                        </form>
                    @endunless
                    <button type="button"
                        @click="edit = {{ Js::from([
                            'id' => $address->id,
                            'label' => $address->label,
                            'full_name' => $address->full_name,
                            'phone' => $address->phone,
                            'line1' => $address->line1,
                            'line2' => $address->line2,
                            'city' => $address->city,
                            'state' => $address->state,
                            'pincode' => $address->pincode,
                            'country' => $address->country,
                        ]) }}; showForm = true; window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' })"
                        class="rounded border border-gray-200 px-3 py-1 text-gray-600 hover:bg-gray-50">Edit</button>
                    <form method="POST" action="{{ route('addresses.destroy', $address->id) }}" onsubmit="return confirm('Delete this address?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded border border-red-200 px-3 py-1 text-red-600 hover:bg-red-50">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add / edit form --}}
    <section x-show="showForm" x-transition class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="edit ? 'Edit Address' : 'Add Address'"></h2>
        <form method="POST" :action="edit ? '{{ url('addresses') }}/' + edit.id : '{{ route('addresses.store') }}'" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            @csrf
            <template x-if="edit">
                <input type="hidden" name="_method" value="PUT" />
            </template>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Label</label>
                <input type="text" name="label" x-bind:value="edit ? edit.label : ''" placeholder="Home, Office…" class="form-input mt-1 text-sm" />
                @error('label')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                <input type="text" name="full_name" x-bind:value="edit ? edit.full_name : ''" class="form-input mt-1 text-sm" />
                @error('full_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                <input type="tel" name="phone" x-bind:value="edit ? edit.phone : ''" class="form-input mt-1 text-sm" />
                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pincode</label>
                <input type="text" name="pincode" x-bind:value="edit ? edit.pincode : ''" maxlength="6" inputmode="numeric" class="form-input mt-1 text-sm" />
                @error('pincode')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address line 1</label>
                <input type="text" name="line1" x-bind:value="edit ? edit.line1 : ''" class="form-input mt-1 text-sm" />
                @error('line1')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address line 2 (optional)</label>
                <input type="text" name="line2" x-bind:value="edit ? (edit.line2 || '') : ''" class="form-input mt-1 text-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">City</label>
                <input type="text" name="city" x-bind:value="edit ? edit.city : ''" class="form-input mt-1 text-sm" />
                @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">State</label>
                <input type="text" name="state" x-bind:value="edit ? edit.state : ''" class="form-input mt-1 text-sm" />
                @error('state')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Country</label>
                <input type="text" name="country" x-bind:value="edit ? edit.country : 'India'" class="form-input mt-1 text-sm" />
                @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-2 sm:col-span-2">
                <button type="submit" class="btn-primary text-sm">Save Address</button>
                <button type="button" @click="showForm = false; edit = null" class="btn-secondary text-sm">Cancel</button>
            </div>
        </form>
    </section>
</div>
@endsection
