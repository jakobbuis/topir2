<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $this->verifyRequestSignature($request);

        $eventData = $this->completeEvent($request->all());

        Event::create(['data' => $eventData]);

        return response(null, 200);
    }

    private function verifyRequestSignature(Request $request): void
    {
        // Verify the request has a valid signature
        $signature = $request->header('X-Todoist-Hmac-SHA256');
        if (empty($signature)) {
            Log::warning('Rejected webhook payload without signature', ['payload' => $request->getContent()]);
            abort(403);
        }

        // Verify the signature
        $secret = config('services.todoist.client_secret');
        $expectedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), $secret, true));
        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('Rejected webhook payload with invalid signature', [
                'payload' => $request->getContent(),
                'signature_expected' => $expectedSignature,
                'signature_actual' => $signature,
            ]);
            abort(403);
        }
    }

    private function completeEvent(array $event): array
    {
        // Completing a recurring tasks sets the `date_completed` parameter of the event to null
        // Insert the current datetime to fix that.
        if (isset($event['event_name']) && $event['event_name'] === 'item:completed') {
            $event['event_data']['date_completed'] ??= Carbon::now()->toISO8601String();
        }

        // Uncompleting tasks events come without a date added
        if (isset($event['event_name']) && $event['event_name'] === 'item:uncompleted') {
            $event['event_data']['date_uncompleted'] ??= Carbon::now()->toISO8601String();
        }

        return $event;
    }
}
