<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;

class AdminPortalController extends Controller
{
    public function summary()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'users' => User::count(),
                'orders' => Order::count(),
                'menu_items' => MenuItem::count(),
                'processing_orders' => Order::where('status', 'Pending')->count(),
                'completed_orders' => Order::where('status', 'Completed')->count(),
            ],
        ]);
    }
}
