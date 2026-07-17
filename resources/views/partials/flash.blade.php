@if (session('success') || session('error') || session('status') || $errors->any())
<div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8" x-data="{ show: true }" x-show="show">
    @if (session('success'))
        <div class="mb-2 flex items-start gap-3 rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-sm text-brand-800" role="status">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button type="button" @click="show = false" class="text-brand-600 hover:text-brand-800" aria-label="Dismiss">&times;</button>
        </div>
    @endif
    @if (session('status'))
        <div class="mb-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800" role="status">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-2 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            <span class="flex-1">{{ session('error') }}</span>
            <button type="button" @click="show = false" class="text-red-600 hover:text-red-800" aria-label="Dismiss">&times;</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endif
