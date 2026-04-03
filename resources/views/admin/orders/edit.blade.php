@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Order</h2><p class="text-sm text-gray-500 dark:text-gray-400">Update order details and line items.</p></div>
    @include('admin.orders.partials.form', ['action' => route('admin.orders.update', $order), 'method' => 'PUT', 'order' => $order, 'products' => $products])
</div>
@endsection
