@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Product</h2><p class="text-sm text-gray-500 dark:text-gray-400">Update product data for the WhatsApp storefront.</p></div>
    @include('admin.products.partials.form', ['action' => route('admin.products.update', $product), 'method' => 'PUT', 'product' => $product, 'categories' => $categories])
</div>
@endsection
