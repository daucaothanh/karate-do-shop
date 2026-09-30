<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$order = Order::latest()->first();
if ($order) {
    echo "Testing order ID: {$order->id}\n";
    $order->load([
        'user',
        'shippingAddress',
        'orderItems.product.images',
        'payment',
        'handledByStaff',
        'orderStatusHistories' => function ($q) {
            $q->latest();
        }
    ]);
    echo "Order loaded successfully!\n";
    echo "- Customer: " . ($order->user->name ?? 'N/A') . "\n";
    echo "- Address: " . ($order->shippingAddress->address ?? 'N/A') . "\n";
    echo "- Payment: " . ($order->payment->payment_method ?? 'N/A') . "\n";
    echo "- Items count: " . $order->orderItems->count() . "\n";
    echo "- History count: " . $order->orderStatusHistories->count() . "\n";
} else {
    echo "No orders found.\n";
}
