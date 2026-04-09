<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function show(string $token): View
    {
        $order = Order::query()
            ->with(['items.product', 'customer', 'store'])
            ->where('metadata->public_token', $token)
            ->firstOrFail();

        return view('website.orders.show', compact('order'));
    }
}
