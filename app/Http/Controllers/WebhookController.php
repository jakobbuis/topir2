<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Verify the request has a valid signature
        $signature = $request->header('X-Todoist-Hmac-SHA256');
        if (empty($signature)) {
            abort(403);
        }

        // Verify the signature
        $expectedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), config('services.todoist.client_secret')));
        if (!hash_equals($expectedSignature, $signature)) {
            abort(403);
        }

        Event::create(['data' => $request->all()]);
        return response(null, 200);
    }
}
