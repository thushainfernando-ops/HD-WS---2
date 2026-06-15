<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $editingCustomer = null;

        if ($request->filled('edit')) {
            $editingCustomer = User::findOrFail($request->integer('edit'));
        }

        $customers = User::where('role', 'user')
            ->withCount('orders')
            ->latest('user_id')
            ->get();

        return view('admin.customers', [
            'customers' => $customers,
            'editingCustomer' => $editingCustomer,
        ]);
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:30',
            'role' => 'required|in:user,admin',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Customer details updated successfully.');
    }

    public function destroy(User $customer)
    {
        if (auth()->id() === $customer->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $customer->delete();

        return back()->with('success', 'Customer account removed successfully.');
    }
}
