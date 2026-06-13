@extends('layouts.admin')

@section('content')
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

    <div class="grid gap-6 xl:grid-cols-[1fr_1.3fr]">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-200">{{ $editingCustomer ? 'Edit customer' : 'Customer overview' }}</p>
            <h2 class="mt-3 text-2xl font-bold text-white">{{ $editingCustomer ? 'Update customer details' : 'Manage registered customers' }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-300">Update names, emails, phone numbers, or acccess roles for customer accounts.</p>

            @if ($editingCustomer)
                <form method="POST" action="{{ route('admin.customers.update', $editingCustomer->id) }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Customer name</label>
                        <input type="text" name="name" value="{{ old('name', $editingCustomer->name) }}" class="input-field mt-2 bg-slate-950/80 text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Email</label>
                        <input type="email" name="email" value="{{ old('email', $editingCustomer->email) }}" class="input-field mt-2 bg-slate-950/80 text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $editingCustomer->phone) }}" class="input-field mt-2 bg-slate-950/80 text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-100">Role</label>
                        <select name="role" class="input-field mt-2 bg-slate-950/80 text-white">
                            <option value="user" {{ old('role', $editingCustomer->role) === 'user' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role', $editingCustomer->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-primary">Save changes</button>
                        <a href="{{ route('admin.customers') }}" class="btn-outline">Cancel</a>
                    </div>
                </form>
            @else
                <div class="mt-6 rounded-[24px] border border-dashed border-white/10 px-4 py-6 text-sm leading-6 text-slate-300">
                    Select a customer in the table to update their record. You can also remove old accounts if needed.
                </div>
            @endif
        </div>

        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-200">Customers</p>
                    <h2 class="mt-3 text-2xl font-bold text-white">Registered customer accounts</h2>
                </div>
                <span class="rounded-full bg-white/10 px-3 py-1 text-sm text-slate-100">{{ $customers->count() }} active</span>
            </div>

            <div class="mt-6 space-y-3">
                @forelse ($customers as $customer)
                    <div class="rounded-[24px] border border-white/10 bg-slate-950/60 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-lg font-bold text-white">{{ $customer->name }}</p>
                                <p class="mt-1 text-sm text-slate-300">{{ $customer->email }}</p>
                                <p class="mt-1 text-sm text-slate-300">Phone: {{ $customer->phone ?? 'Not provided' }}</p>
                                <p class="mt-1 text-sm text-slate-300">Orders: {{ $customer->orders_count }} • Role: {{ $customer->role }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.customers', ['edit' => $customer->id]) }}" class="rounded-full border border-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">Edit</a>
                                @if ($customer->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer->id) }}" onsubmit="return confirm('Delete this customer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-rose-500 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-600">Remove</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[24px] border border-dashed border-white/10 px-4 py-8 text-center text-sm text-slate-300">
                        No customer accounts found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
