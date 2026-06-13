<?php

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    // Only allow the owner of the order (or admin) to listen to order updates.
    $order = Order::where('order_id', $orderId)->first();
    if (! $order) return false;
    return $user->id === $order->user_id || $user->is_admin ?? false;
});
