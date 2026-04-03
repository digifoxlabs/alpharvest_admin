@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create Product</h2><p class="text-sm text-gray-500 dark:text-gray-400">Add a Meta/WhatsApp catalog product.</p></div>
    @include('admin.products.partials.form', ['action' => route('admin.products.store'), 'method' => 'POST', 'product' => null, 'categories' => $categories])
</div>
@endsection
