@extends('layouts.admin')

@section('title', 'Edit Store')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit WhatsApp Store</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update Meta credentials and WhatsApp storefront content.</p>
    </div>

    @include('admin.stores.partials.form', [
        'action' => route('admin.stores.update', $store),
        'method' => 'PUT',
        'store' => $store,
        'deliveryZonesText' => $deliveryZonesText,
        'undeliverableMessage' => $undeliverableMessage,
    ])
</div>
@endsection
