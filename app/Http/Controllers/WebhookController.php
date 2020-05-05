<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Verify the request has a valid signature
        $signature = $request->header('X-Todoist-Hmac-SHA256');
        if (empty($signature)) {
            Log::warning('Rejected webhook payload without signature', ['payload' => $request->getContent()]);
            abort(403);
        }

        // Verify the signature
        $expectedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), config('services.todoist.client_secret'), true));
        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Rejected webhook payload with invalid signature', [
                'payload' => $request->getContent(),
                'signature_expected' => $expectedSignature,
                'signature_actual' => $signature,
            ]);
            abort(403);
        }

        // Completing a recurring tasks sets the `date_completed` parameter of the event to null
        // Use the current datetime to fix that here
        $eventData = $request->all();
        if (isset($eventData['event_name']) && $eventData['event_name'] ===  'item:completed') {
            $eventData['event_data']['date_completed'] ??= Carbon::now()->toISO8601String();
        }

        Event::create(['data' => $eventData]);
        return response(null, 200);
    }
}
