@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Customer</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update customer details and delivery addresses stored in metadata.</p>
    </div>

    @php($delivery = (array) data_get($customer->metadata, 'delivery', []))
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Store</label>
                    <select name="store_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" @selected(old('store_id', $customer->store_id) == $store->id)>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp ID</label>
                    <input type="text" name="whatsapp_id" value="{{ old('whatsapp_id', $customer->whatsapp_id) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Language</label>
                    <input type="text" name="preferred_language" value="{{ old('preferred_language', $customer->preferred_language) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $customer->pincode) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Delivery Pincode</label>
                    <input type="text" name="current_delivery_pincode" value="{{ old('current_delivery_pincode', $delivery['pincode'] ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Delivery City</label>
                    <input type="text" name="current_delivery_city" value="{{ old('current_delivery_city', $delivery['city'] ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Current Delivery Address</label>
                    <input type="text" name="current_delivery_address" value="{{ old('current_delivery_address', $delivery['address'] ?? '') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Address Book</label>
                <textarea name="address_book_text" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('address_book_text', $addressBookText) }}</textarea>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">One address per line using `pincode | city | address`.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Save Customer</button>
                <a href="{{ route('admin.customers.show', $customer) }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
