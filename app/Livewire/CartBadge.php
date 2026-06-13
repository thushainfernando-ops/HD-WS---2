<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartBadge extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        $count = Auth::check()
            ? CartItem::where('user_id', Auth::id())->sum('quantity')
            : 0;

        return view('livewire.cart-badge', compact('count'));
    }
}
