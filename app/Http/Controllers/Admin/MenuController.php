<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //read
    public function index(Request $request)
    {
        $editingItem = null;

        if ($request->filled('edit')) {
            $editingItem = MenuItem::findOrFail($request->integer('edit'));
        }

        return view('admin.menu', [
            'menuItems' => MenuItem::latest('item_id')->get(),
            'editingItem' => $editingItem,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:80',
            'image' => 'nullable|string|max:200',
        ]);
//create
        MenuItem::create($validated);

        return back()->with('success', 'Menu item added successfully.');
    }
//update
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:80',
            'image' => 'nullable|string|max:200',
        ]);

        $menuItem->update($validated);

        return back()->with('success', 'Menu item updated successfully.');
    }
//delete
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return back()->with('success', 'Menu item removed successfully.');
    }
}
