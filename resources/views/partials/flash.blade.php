@php
    $flashMap = [
        'success' => ['bg' => 'bg-brand-50', 'border' => 'border-brand-200', 'text' => 'text-brand-800', 'icon' => 'M4.5 12.75l6 6 9-13.5'],
        'error'   => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'icon' => 'M6 18L18 6M6 6l12 12'],
        'status'  => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

@foreach ($flashMap as $key => $style)
    @if (session($key))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 6000)"
            role="alert"
            class="mx-auto mt-4 flex max-w-7xl items-start gap-3 rounded-lg border {{ $style['border'] }} {{ $style['bg'] }} px-4 py-3 {{ $style['text'] }} sm:px-6 lg:px-8"
        >
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['icon'] }}" />
            </svg>
            <p class="flex-1 text-sm font-medium">{{ session($key) }}</p>
            <button type="button" @click="show = false" class="shrink-0 rounded p-0.5 hover:bg-black/5 focus:outline-none" aria-label="Dismiss">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
@endforeach
