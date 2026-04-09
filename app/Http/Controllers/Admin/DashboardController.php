<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->input('status', '');

        $ordersQuery = Order::query()
            ->with(['customer'])
            ->when(in_array($status, ['pending', 'processing', 'dispatched', 'delivered', 'cancelled'], true), function ($query) use ($status) {
                $query->where('status', $status);
            });

        $orders = (clone $ordersQuery)
            ->latest('placed_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.dashboard.index', [
            'stats' => [
                'pending' => Order::query()->where('status', 'pending')->count(),
                'processing' => Order::query()->where('status', 'processing')->count(),
                'dispatched' => Order::query()->where('status', 'dispatched')->count(),
                'delivered' => Order::query()->where('status', 'delivered')->count(),
            ],
            'orders' => $orders,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $status = (string) $request->input('status', '');
        $orders = Order::query()
            ->with('customer')
            ->when(in_array($status, ['pending', 'processing', 'dispatched', 'delivered', 'cancelled'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('placed_at')
            ->latest('id')
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Sl No', 'Order Number', 'Customer Name', 'Status', 'Payment Status', 'Delivery Address', 'Total Amount', 'Date']);

            foreach ($orders as $index => $order) {
                $delivery = (array) data_get($order->metadata, 'delivery', []);

                fputcsv($handle, [
                    $index + 1,
                    $order->order_number,
                    $order->customer?->name ?: $order->customer?->phone ?: 'Guest',
                    $order->status,
                    $order->payment_status,
                    trim(implode(', ', array_filter([
                        $delivery['address'] ?? null,
                        $delivery['city'] ?? null,
                        $delivery['pincode'] ?? null,
                    ]))),
                    number_format((float) $order->total, 2, '.', ''),
                    optional($order->placed_at ?: $order->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 'orders-export.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
