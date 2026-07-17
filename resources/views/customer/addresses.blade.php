@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ showForm: false, edit: null }">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Addresses</h1>
        <button type="button" @click="showForm = true; edit = null" class="btn-primary">Add address</button>
    </div>

    {{-- Saved addresses --}}
    @if ($addresses->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-900">
            <p class="text-gray-500 dark:text-gray-400">No saved addresses yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($addresses as $address)
                <div class="relative rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    @if ($address->is_default)
                        <span class="absolute right-4 top-4 rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-800 dark:bg-brand-900/40 dark:text-brand-300">Default</span>
                    @endif
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $address->label }}</p>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $address->full_name }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $address->singleLine() }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $address->country }} · {{ $address->phone }}</p>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                        <button type="button"
                            @click='edit = @json([
                                "id" => $address->id, "label" => $address->label, "full_name" => $address->full_name,
                                "phone" => $address->phone, "line1" => $address->line1, "line2" => $address->line2,
                                "city" => $address->city, "state" => $address->state, "pincode" => $address->pincode,
                                "country" => $address->country,
                            ]); showForm = true'
                            class="text-brand-600 hover:underline">Edit</button>
                        @unless ($address->is_default)
                            <form method="POST" action="{{ route('addresses.default', $address) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-gray-600 hover:underline dark:text-gray-300">Make default</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="return confirm('Delete this address?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Add / edit form --}}
    <div x-show="showForm" x-cloak x-transition class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white" x-text="edit ? 'Edit address' : 'New address'"></h2>
        <form method="POST" :action="edit ? '{{ url('addresses') }}/' + edit.id : '{{ route('addresses.store') }}'" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @csrf
            <template x-if="edit"><input type="hidden" name="_method" value="PATCH" /></template>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Label</label>
                <input name="label" type="text" :value="edit ? edit.label : ''" placeholder="Home / Office" required class="form-input" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Full name</label>
                <input name="full_name" type="text" :value="edit ? edit.full_name : '{{ auth()->user()->name }}'" required class="form-input" />
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Address line 1</label>
                <input name="line1" type="text" :value="edit ? edit.line1 : ''" required class="form-input" />
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Address line 2</label>
                <input name="line2" type="text" :value="edit ? (edit.line2 || '') : ''" class="form-input" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">City</label>
                <input name="city" type="text" :value="edit ? edit.city : ''" required class="form-input" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">State</label>
                <input name="state" type="text" :value="edit ? edit.state : ''" required class="form-input" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Pincode</label>
                <input name="pincode" type="text" inputmode="numeric" :value="edit ? edit.pincode : ''" required class="form-input" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Country</label>
                <input name="country" type="text" :value="edit ? edit.country : 'India'" required class="form-input" />
            </div>
            <div class="flex justify-end gap-3 sm:col-span-2">
                <button type="button" @click="showForm = false" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save address</button>
            </div>
        </form>
    </div>
</div>
@endsection
