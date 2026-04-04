@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">WhatsApp Chats</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track inbound customer messages and outbound WhatsApp delivery, read, and failure states.</p>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs uppercase tracking-wide text-gray-400">Messages</p>
                <p class="mt-2 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs uppercase tracking-wide text-gray-400">Inbound</p>
                <p class="mt-2 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['inbound'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs uppercase tracking-wide text-gray-400">Outbound</p>
                <p class="mt-2 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['outbound'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-xs uppercase tracking-wide text-gray-400">Filtered</p>
                <p class="mt-2 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $stats['filtered'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search messages</label>
                <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Body, WhatsApp ID, customer, store" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Store</label>
                <select name="store_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All stores</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" @selected($filters['store_id'] === (string) $store->id)>{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Direction</label>
                <select name="direction" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All directions</option>
                    <option value="inbound" @selected($filters['direction'] === 'inbound')>Inbound</option>
                    <option value="outbound" @selected($filters['direction'] === 'outbound')>Outbound</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">All statuses</option>
                    <option value="received" @selected($filters['status'] === 'received')>Received</option>
                    <option value="queued" @selected($filters['status'] === 'queued')>Queued</option>
                    <option value="sent" @selected($filters['status'] === 'sent')>Sent</option>
                    <option value="delivered" @selected($filters['status'] === 'delivered')>Delivered</option>
                    <option value="read" @selected($filters['status'] === 'read')>Read</option>
                    <option value="failed" @selected($filters['status'] === 'failed')>Failed</option>
                </select>
            </div>
            <div class="flex items-end gap-3 xl:col-span-5">
                <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Apply Filters</button>
                <a href="{{ route('admin.messages.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Store / Customer</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Message</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Direction</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-4 font-medium text-gray-600 dark:text-gray-300">Timeline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr class="border-b border-gray-100 align-top dark:border-gray-800">
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-800 dark:text-white/90">{{ $message->conversation?->store?->name ?: 'Unknown store' }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->conversation?->customer?->name ?: ($message->conversation?->customer?->phone ?: 'Unknown customer') }}
                                </div>
                                @if ($message->conversation?->customer?->phone)
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $message->conversation->customer->phone }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-gray-700 dark:text-gray-300">{{ $message->body ?: 'No message body stored.' }}</div>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Type: {{ $message->type }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 break-all">
                                    WhatsApp ID:
                                    @if ($message->whatsapp_message_id)
                                        {{ $message->whatsapp_message_id }}
                                    @elseif ($message->direction === 'outbound' && $message->status_label === 'Failed')
                                        Not assigned because Meta rejected the message
                                    @else
                                        Not available
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    {{ ucfirst($message->direction) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @php($toneClasses = ['success' => 'bg-green-100 text-green-700', 'danger' => 'bg-red-100 text-red-700', 'warning' => 'bg-amber-100 text-amber-700'])
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $toneClasses[$message->status_tone] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $message->status_label }}
                                </span>
                                @if ($message->status_detail)
                                    <p class="mt-2 max-w-xs text-xs text-gray-500 dark:text-gray-400">{{ $message->status_detail }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">
                                <div>Sent: {{ $message->sent_at?->format('Y-m-d H:i:s') ?: 'Not sent' }}</div>
                                <div class="mt-1">Delivered: {{ $message->delivered_at?->format('Y-m-d H:i:s') ?: 'Pending' }}</div>
                                <div class="mt-1">Read: {{ $message->read_at?->format('Y-m-d H:i:s') ?: 'Pending' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No messages yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $messages->links() }}</div>
</div>
@endsection
