@extends('layouts.admin')

@section('title', 'Create Order')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create Order</h2><p class="text-sm text-gray-500 dark:text-gray-400">Create an order and attach line items.</p></div>
    @include('admin.orders.partials.form', ['action' => route('admin.orders.store'), 'method' => 'POST', 'order' => null, 'products' => $products])
</div>
@endsection
