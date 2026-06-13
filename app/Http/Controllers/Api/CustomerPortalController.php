<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    public function cart(Request $request)
    {
        $cartItems = $request->user()
            ->cartItems()
            ->with('menuItem')
            ->get()
            ->map(function ($item) {
                return [
                    'cart_id' => $item->cart_id,
                    'quantity' => $item->quantity,
                    'item' => [
                        'id' => $item->menuItem->item_id,
                        'name' => $item->menuItem->name,
                        'price' => (float) $item->menuItem->price,
                        'category' => $item->menuItem->category,
                        'image' => $item->menuItem->image,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cartItems,
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->latest('order_id')
            ->get()
            ->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'total_amount' => (float) $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
