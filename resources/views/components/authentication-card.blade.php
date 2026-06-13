<div class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto grid w-full max-w-4xl overflow-hidden rounded-[20px] bg-white shadow-lg md:grid-cols-[0.6fr_1fr]">
        <div class="flex items-center justify-center bg-gradient-to-b from-slate-900 to-sky-800 p-8 text-white">
            <div class="text-center">
                {{ $logo }}
                <h2 class="mt-4 text-2xl font-bold">Hot Dish</h2>
            </div>
        </div>

        <div class="p-6 sm:p-8 lg:p-10">
            {{ $slot }}
        </div>
    </div>
</div>
