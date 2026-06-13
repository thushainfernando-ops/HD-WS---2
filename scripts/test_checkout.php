<?php
// scripts/test_checkout.php
// Simulate a checkout flow programmatically using Laravel's container

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Create or fetch a test user
$user = User::firstOrCreate(
    ['email' => 'testuser@example.com'],
    ['name' => 'Test User', 'password' => password_hash('Password123!', PASSWORD_DEFAULT)]
);

// Ensure a menu item exists
$menu = MenuItem::first();
if (! $menu) {
    $menu = MenuItem::create(['name' => 'Demo Dish', 'price' => 500]);
}

// Add a cart item
CartItem::updateOrCreate([
    'user_id' => $user->id,
    'item_id' => $menu->getKey(),
], ['quantity' => 1]);

// Log in the user for the request context
Auth::login($user);

// Create a request mimicking form submission
$request = Request::create('/checkout', 'POST', [
    'address' => '123 Test Street, Colombo',
    'phone' => '+94112345678',
    'payment_method' => 'card',
    'stripe_token' => 'tok_visa',
]);

// Bind the session and user
$request->setLaravelSession(app('session')->driver());
$request->setUserResolver(function () use ($user) { return $user; });

// Call the controller
$controller = app()->make(App\Http\Controllers\CheckoutController::class);
$response = $controller->process($request);

// Output
if ($response instanceof Illuminate\Http\RedirectResponse) {
    echo "Redirected to: " . $response->getTargetUrl() . PHP_EOL;
} else {
    echo "Response: ";
    var_dump($response);
}

echo "Done\n";
