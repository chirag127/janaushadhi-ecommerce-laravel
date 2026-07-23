@extends('layouts.admin')

@section('title', 'Customers')
@section('heading', 'Customers')

@section('admin')
    <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4 flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ $search }}" placeholder="Search by name or email…" class="form-input max-w-sm text-sm" />
        <button type="submit" class="btn-primary text-sm">Search</button>
        <a href="{{ route('admin.customers.index') }}" class="btn-secondary text-sm">Reset</a>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Joined</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $customer->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $customer->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-3"><span class="badge {{ $customer->is_active ? 'bg-brand-100 text-brand-800' : 'bg-red-100 text-red-800' }}">{{ $customer->is_active ? 'Active' : 'Disabled' }}</span></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-brand-700 hover:underline">View</a>
                                    <form method="POST" action="{{ route('admin.customers.toggle', $customer->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $customer->is_active ? 'text-red-600' : 'text-brand-700' }} hover:underline">{{ $customer->is_active ? 'Disable' : 'Enable' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $customers->links() }}</div>
@endsection
