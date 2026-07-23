<div>
    @if ($count > 0)
        <span class="absolute -right-1 -top-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-brand-600 px-1 text-[0.65rem] font-bold text-white">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</div>
