<span>
@if ($count > 0)
    <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-accent-500 px-1 text-[10px] font-bold text-white">{{ $count > 99 ? '99+' : $count }}</span>
@endif
</span>
