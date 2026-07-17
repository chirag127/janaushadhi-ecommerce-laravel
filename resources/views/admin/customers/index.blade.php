@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
    <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search name / email…" class="form-input text-sm" />
    <button type="submit" class="btn-secondary">Search</button>
</form>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $customer->email }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($customer->is_active)
                                <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-800 dark:bg-brand-900/40 dark:text-brand-300">Active</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/40 dark:text-red-300">Disabled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ optional($customer->created_at)->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="text-brand-600 hover:underline">View</a>
                                <form method="POST" action="{{ route('admin.customers.toggle', $customer) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-gray-600 hover:underline dark:text-gray-300">{{ $customer->is_active ? 'Disable' : 'Enable' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $customers->links() }}</div>
@endsection
