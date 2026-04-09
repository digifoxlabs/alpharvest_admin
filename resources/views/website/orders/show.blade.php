@extends('layouts.website')

@section('title', 'Order Details')
@section('hide_footer', '1')

@section('content')
@php($delivery = (array) data_get($order->metadata, 'delivery', []))
<section class="mx-auto max-w-5xl px-4 py-12">
    <div class="rounded-3xl border border-[#d5e0cc] bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-2xl font-semibold text-[#1f3b1a]">Order {{ $order->order_number }}</h1>
        <p class="mt-2 text-sm text-[#5f6f55]">Status: {{ \Illuminate\Support\Str::headline($order->status) }} | Payment: {{ \Illuminate\Support\Str::headline($order->payment_status) }}</p>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div>
                <h2 class="text-lg font-semibold text-[#1f3b1a]">Customer</h2>
                <p class="mt-3 text-sm text-[#44523d]">{{ $order->customer?->name ?: 'Guest customer' }}</p>
                @if ($order->customer?->phone)
                    <p class="mt-1 text-sm">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $order->customer->phone) }}" class="font-medium text-[#2d5a27] underline decoration-[#9dbc7f] underline-offset-4">
                            {{ $order->customer->phone }}
                        </a>
                    </p>
                @else
                    <p class="mt-1 text-sm text-[#44523d]">Phone not available</p>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-semibold text-[#1f3b1a]">Delivery Address</h2>
                <p class="mt-3 text-sm text-[#44523d]">{{ implode(', ', array_filter([$delivery['address'] ?? null, $delivery['city'] ?? null, $delivery['pincode'] ?? null])) ?: 'Address not available yet' }}</p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold text-[#1f3b1a]">Items</h2>
            <div class="mt-4 space-y-4">
                @foreach ($order->items as $item)
                    <div class="rounded-2xl border border-[#d5e0cc] p-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-[#1f3b1a]">{{ $item->product_name }}</p>
                                <p class="mt-1 text-sm text-[#5f6f55]">SKU: {{ $item->sku ?: 'Not available' }}</p>
                            </div>
                            <div class="text-right text-sm text-[#44523d]">
                                <p>Quantity: {{ $item->quantity }}</p>
                                <p class="mt-1">Amount: {{ number_format((float) $item->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-[#d5e0cc] bg-[#f7fbf2] p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-[#5f6f55]">Total Order Amount</p>
                    <p class="text-lg font-semibold text-[#1f3b1a]">{{ number_format((float) $order->total, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
