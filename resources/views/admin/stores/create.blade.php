@extends('layouts.admin')

@section('title', 'Create Store')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Create WhatsApp Store</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Add Meta account and messaging settings for a store.</p>
    </div>

    @include('admin.stores.partials.form', [
        'action' => route('admin.stores.store'),
        'method' => 'POST',
        'store' => null,
    ])
</div>
@endsection
