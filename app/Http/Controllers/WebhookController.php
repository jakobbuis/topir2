<?php

namespace App\Http\Controllers;

use App\Event;
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
        $expectedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), config('services.todoist.client_secret')));
        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Rejected webhook payload with invalid signature', [
                'payload' => $request->getContent(),
                'signature_expected' => $expectedSignature,
                'signature_actual' => $signature,
            ]);
            abort(403);
        }

        Event::create(['data' => $request->all()]);
        return response(null, 200);
    }
}
