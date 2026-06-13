@extends('layouts.admin')

@section('content')
@php
    $title = 'Menu management';
@endphp

<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-[24px] border border-emerald-300/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-[24px] border border-rose-300/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1fr_1.2fr]">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-200">{{ $editingItem ? 'Edit menu item' : 'Add new menu item' }}</p>
                <h2 class="mt-3 text-2xl font-bold text-white">{{ $editingItem ? 'Update a menu item' : 'Create a new dish' }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-300">Add a dish, change pricing, or update the image path for your menu.</p>

                <form method="POST" action="{{ $editingItem ? route('admin.menu.update', $editingItem->item_id) : route('admin.menu.store') }}" class="mt-6 space-y-4">
                    @csrf
                    @if ($editingItem)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Dish name</label>
                        <input type="text" name="name" value="{{ old('name', $editingItem->name ?? '') }}" class="input-field mt-2 bg-slate-950/80 text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Category</label>
                        <input type="text" name="category" value="{{ old('category', $editingItem->category ?? '') }}" class="input-field mt-2 bg-slate-950/80 text-white" required>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-100">Price (LKR)</label>
                            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $editingItem->price ?? '') }}" class="input-field mt-2 bg-slate-950/80 text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-100">Image path</label>
                            <input type="text" name="image" value="{{ old('image', $editingItem->image ?? '') }}" placeholder="assets/food-image.jpg" class="input-field mt-2 bg-slate-950/80 text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Description</label>
                        <textarea name="description" rows="4" class="input-field mt-2 bg-slate-950/80 text-white">{{ old('description', $editingItem->description ?? '') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-primary">{{ $editingItem ? 'Update item' : 'Add item' }}</button>
                        @if ($editingItem)
                            <a href="{{ route('admin.menu') }}" class="btn-outline">Cancel</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-200">Live menu</p>
                <h2 class="mt-3 text-2xl font-bold text-white">Current dishes</h2>
                @php
                    $menuItems = is_iterable($menuItems ?? null) ? $menuItems : collect();
                @endphp
                <div class="mt-6 space-y-3">
                    @forelse ($menuItems as $item)
                        <div class="rounded-[24px] border border-white/10 bg-slate-950/60 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-lg font-bold text-white">{{ $item->name }}</p>
                                    <p class="mt-1 text-sm text-slate-300">{{ $item->category }} • Rs. {{ number_format($item->price, 2) }}</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-300">{{ Str::limit($item->description, 120) }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.menu', ['edit' => $item->item_id]) }}" class="rounded-full border border-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">Edit</a>
                                    <form method="POST" action="{{ route('admin.menu.destroy', $item->item_id) }}" onsubmit="return confirm('Delete this menu item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-500 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-600">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[24px] border border-dashed border-white/10 px-4 py-8 text-center text-sm text-slate-300">
                            No menu items yet. Add your first dish above.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
