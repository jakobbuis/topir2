<?php

namespace Tests\Feature;

use App\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Prevent updating projections
        \Illuminate\Support\Facades\Event::fake();

        // Set the client secret to a predictable value to enable hardcoding
        // HMAC headers
        Config::set('services.todoist.client_secret', '1234');
    }

    /** @test */
    public function itAlwaysRespondsWith200Ok()
    {
        $payload = ['foo' => 'bar'];
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC($payload))
            ->postJson(route('webhook'), $payload);

        $response->assertOk();
    }

    /** @test */
    public function itStoresTheIncomingEvent()
    {
        $this->assertCount(0, Event::all());

        $payload = ['foo' => 'bar'];
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC($payload))
            ->postJson(route('webhook'), $payload);

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
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC(['foo' => null]))
            ->postJson(route('webhook'), ['foo' => 'bar']);

        $response->assertForbidden();
    }

    /** @test */
    public function payloadsWithDateCompletedAreAcceptedAsIs()
    {
        Carbon::setTestNow('2020-05-03 08:00:00');

        $this->assertCount(0, Event::all());

        $payload = [
            'event_data' => (object) [
                'date_completed' => '2020-05-01T08:00:00Z',
            ],
            'event_name' => 'item:completed',
        ];
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC($payload))
            ->postJson(route('webhook'), $payload);

        $response->assertOk();
        $this->assertCount(1, Event::all());
        $this->assertEquals('2020-05-01T08:00:00Z', Event::first()->data->event_data->date_completed);
    }

    /** @test */
    public function payloadsWithMissingDateCompletedAreFilledIn()
    {
        Carbon::setTestNow('2020-05-03 08:00:00');

        $this->assertCount(0, Event::all());

        $payload = [
            'event_data' => (object) [
                'date_completed' => null,
            ],
            'event_name' => 'item:completed',
        ];
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC($payload))
            ->postJson(route('webhook'), $payload);

        $response->assertOk();
        $this->assertCount(1, Event::all());
        $this->assertEquals('2020-05-03T08:00:00+00:00', Event::first()->data->event_data->date_completed);
    }

    /** @test */
    public function itemsUncompletedHaveTheirDateAdded()
    {
        Carbon::setTestNow('2020-05-03 08:00:00');

        $this->assertCount(0, Event::all());

        $payload = [
            'event_data' => (object) [],
            'event_name' => 'item:uncompleted',
        ];
        $response = $this->withHeader('X-Todoist-Hmac-SHA256', $this->validHMAC($payload))
            ->postJson(route('webhook'), $payload);

        $response->assertOk();
        $this->assertCount(1, Event::all());
        $this->assertEquals('2020-05-03T08:00:00+00:00', Event::first()->data->event_data->date_uncompleted);
    }

    /**
     * Calculate the valid HMAC for a payload
     */
    private function validHMAC(array $payload): string
    {
        return base64_encode(hash_hmac('sha256', json_encode($payload), '1234', true));
    }
}
