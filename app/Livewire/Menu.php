<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\MenuItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class Menu extends Component
{
    public $search = '';
    public $activeCategory = 'all';

    public function mount()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->redirect(route('admin.dashboard'));
        }
    }

    public function render()
    {
        $query = MenuItem::query();

        if ($this->activeCategory !== 'all') {
            $query->where('category', $this->activeCategory);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $items = $query->get();
        $categories = MenuItem::select('category')->distinct()->pluck('category');

        return view('livewire.menu', [
            'items' => $items,
            'categories' => $categories
        ])->layout('layouts.frontend');
    }

    public function setCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function addToCart($itemId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('item_id', $itemId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'item_id' => $itemId,
                'quantity' => 1
            ]);
        }

        $this->dispatch('cartUpdated');
        session()->flash('message', 'Item added to cart!');
    }
}
