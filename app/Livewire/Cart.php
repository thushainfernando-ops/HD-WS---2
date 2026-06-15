<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public function mount()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->redirect(route('admin.dashboard'));
        }
    }

    public function render()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('menuItem')
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->menuItem->price * $item->quantity;
        });
//subtotal is the sum of all cart items, calculated by multiplying the price of each menu item by its quantity and then summing those values together.
        return view('livewire.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'deliveryFee' => 250,
            'total' => $subtotal + 250
        ])->layout('layouts.frontend');
    }
//cart quentity increment, decrement, and remove functions. Each function checks if the cart item belongs to the authenticated user before performing the action. After updating the cart, it dispatches a 'cartUpdated' event to notify other components of the change.
    public function incrementQuantity($cartId)
    {
        $cartItem = CartItem::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->increment('quantity');
            $this->dispatch('cartUpdated');
        }
    }

    public function decrementQuantity($cartId)
    {
        $cartItem = CartItem::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
            // After updating the cart, it dispatches a 'cartUpdated' event to notify other components of the change.
            $this->dispatch('cartUpdated');
        }
    }

    public function removeItem($cartId)
    {
        $cartItem = CartItem::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->dispatch('cartUpdated');
        }
    }
}
