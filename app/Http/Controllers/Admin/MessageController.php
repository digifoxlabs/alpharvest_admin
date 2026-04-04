<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $direction = (string) $request->input('direction', '');
        $status = (string) $request->input('status', '');
        $storeId = (int) $request->input('store_id', 0);

        $messageQuery = Message::query()
            ->with([
                'conversation.store',
                'conversation.customer',
            ]);

        if ($search !== '') {
            $messageQuery->where(function ($query) use ($search) {
                $query
                    ->where('body', 'like', "%{$search}%")
                    ->orWhere('whatsapp_message_id', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhereHas('conversation.customer', function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('whatsapp_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('conversation.store', function ($storeQuery) use ($search) {
                        $storeQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        if (in_array($direction, ['inbound', 'outbound'], true)) {
            $messageQuery->where('direction', $direction);
        }

        if ($storeId > 0) {
            $messageQuery->whereHas('conversation', fn ($query) => $query->where('store_id', $storeId));
        }

        if (in_array($status, ['received', 'queued', 'sent', 'delivered', 'read', 'failed'], true)) {
            $messageQuery = match ($status) {
                'received' => $messageQuery->where('direction', 'inbound'),
                'queued' => $messageQuery
                    ->where('direction', 'outbound')
                    ->whereNull('sent_at')
                    ->whereNull('delivered_at')
                    ->whereNull('read_at')
                    ->where(function ($query) {
                        $query
                            ->whereNull('payload->status_update->status')
                            ->orWhere('payload->status_update->status', '!=', 'failed');
                    })
                    ->where(function ($query) {
                        $query
                            ->whereNull('payload->dispatched')
                            ->orWhere('payload->dispatched', '!=', false);
                    }),
                'sent' => $messageQuery
                    ->where('direction', 'outbound')
                    ->whereNotNull('sent_at')
                    ->whereNull('delivered_at')
                    ->whereNull('read_at'),
                'delivered' => $messageQuery
                    ->where('direction', 'outbound')
                    ->whereNotNull('delivered_at')
                    ->whereNull('read_at'),
                'read' => $messageQuery
                    ->where('direction', 'outbound')
                    ->whereNotNull('read_at'),
                'failed' => $messageQuery
                    ->where('direction', 'outbound')
                    ->where(function ($query) {
                        $query
                            ->where('payload->status_update->status', 'failed')
                            ->orWhere('payload->dispatched', false);
                    }),
            };
        }

        $messages = $messageQuery
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'stores' => Store::query()->orderBy('name')->get(),
            'stats' => [
                'total' => Message::query()->count(),
                'inbound' => Message::query()->where('direction', 'inbound')->count(),
                'outbound' => Message::query()->where('direction', 'outbound')->count(),
                'filtered' => $messages->total(),
            ],
            'filters' => [
                'search' => $search,
                'direction' => $direction,
                'status' => $status,
                'store_id' => $storeId > 0 ? (string) $storeId : '',
            ],
        ]);
    }
}
