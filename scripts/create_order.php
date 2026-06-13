<?php
require realpath(__DIR__ . '/../vendor/autoload.php');
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email','john@example.com')->first();
if (!$user) { echo "USER_NOT_FOUND"; exit(1); }
$order = App\Models\Order::create(['user_id' => $user->id, 'total_amount' => 50.00, 'status' => 'Pending']);
if (!$order) { echo "CREATE_FAILED"; exit(1); }
echo $order->order_id;
