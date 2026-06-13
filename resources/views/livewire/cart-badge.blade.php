@auth
    <a href="{{ route('cart') }}" class="relative rounded-full p-2 text-white transition hover:text-primary-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H5.4" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l-1.2 5.4A1 1 0 006.8 19h10.4a1 1 0 00.98-.8L19 9H7z" />
            <circle cx="9" cy="20" r="1.2" fill="currentColor" />
            <circle cx="16" cy="20" r="1.2" fill="currentColor" />
        </svg>

        @if($count > 0)
            <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary-500 px-1 text-[10px] font-bold text-white">
                {{ $count }}
            </span>
        @endif
    </a>
@endauth
