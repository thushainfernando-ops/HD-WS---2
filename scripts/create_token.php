<?php
$vendor = realpath(__DIR__ . '/../vendor/autoload.php');
require $vendor;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email','john@example.com')->first();
if (!$user) {
    echo "USER_NOT_FOUND";
    exit(1);
}
// Ensure Sanctum's createToken is available
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
