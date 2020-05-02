<?php

namespace Tests\Feature;

use App\Event;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Set the client secret to a predictable value to enable hardcoding
        // HMAC headers
        Config::set('services.todoist.client_secret', '1234');
    }

    /** @test */
    public function itAlwaysRespondsWith200Ok()
    {
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', 'OBRG7pMxrhMzbUTBqhg6Zzi5TE4ta4Dl8b40ZPpYiaQ=')
            ->postJson(route('webhook'), ['foo' => 'bar']);

        $response->assertOk();
    }

    /** @test */
    public function itStoresTheIncomingEvent()
    {
        $this->assertCount(0, Event::all());

        $response = $this->withHeader('X-Todoist-Hmac-SHA256', 'OBRG7pMxrhMzbUTBqhg6Zzi5TE4ta4Dl8b40ZPpYiaQ=')
            ->postJson(route('webhook'), ['foo' => 'bar']);

        $this->assertCount(1, Event::all());
        $this->assertEquals('bar', Event::first()->data->foo);
    }

    /** @test */
    public function payloadsWithoutAValidSignatureAreRejected()
    {
        $response = $this->post(route('webhook'), ['foo' => 'bar']);

        $response->assertForbidden();
    }

    /** @test */
    public function payloadsWithInvalidSignaturesAreRejected()
    {
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', 'OBRG7pMxrhMzbUTBqhg6Zzi5TE4ta4Dl8b40ZPpYiaQ=')
            ->postJson(route('webhook'), ['bar' => 'foo']); // Change the payload to invalidate the key

        $response->assertForbidden();
    }
}
