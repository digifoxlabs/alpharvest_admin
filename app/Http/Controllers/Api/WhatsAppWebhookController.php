<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// class WhatsAppWebhookController extends Controller
// {
//     public function __construct(protected WhatsAppWebhookService $webhooks)
//     {
//     }

//   public function verify(Request $request)
//     {
//         $mode = $request->query('hub_mode');
//         $verifyToken = $request->query('hub_verify_token');
//         $challenge = $request->query('hub_challenge');

//         if (
//             $mode === 'subscribe'
//             && hash_equals((string) config('services.whatsapp.verify_token'), (string) $verifyToken)
//         ) {
//             return response($challenge, 200)->header('Content-Type', 'text/plain');
//         }

//         return response()->json(['message' => 'Invalid verify token.'], 403);
//     }

//     public function handle(Request $request): JsonResponse
//     {
//         $this->webhooks->ingest($request->all());

//         return response()->json(['status' => 'accepted']);
//     }
// }

class WhatsAppWebhookController extends Controller
{
    public function __construct(protected WhatsAppWebhookService $webhooks)
    {
    }

    /**
     * Meta webhook verification endpoint
     */
    public function verify(Request $request)
    {
        Log::info('WhatsApp Webhook VERIFY request received', [
            'query' => $request->query()
        ]);

        $mode = $request->query('hub_mode');
        $verifyToken = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if (
            $mode === 'subscribe'
            && hash_equals(
                (string) config('services.whatsapp.verify_token'),
                (string) $verifyToken
            )
        ) {
            Log::info('WhatsApp Webhook VERIFIED successfully');

            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::error('WhatsApp Webhook verification FAILED', [
            'received_token' => $verifyToken
        ]);

        return response()->json([
            'message' => 'Invalid verify token.'
        ], 403);
    }


    /**
     * Incoming WhatsApp message handler
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('WhatsApp Webhook EVENT received', [
            'payload' => $request->all()
        ]);

        try {

            $this->webhooks->ingest($request->all());

            Log::info('Webhook payload processed successfully');

        } catch (\Throwable $e) {

            Log::error('Webhook processing FAILED', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return response()->json([
            'status' => 'accepted'
        ]);
    }
}
