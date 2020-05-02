<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    /** @test */
    public function itAlwaysRespondsWith200Ok()
    {
        $response = $this->post(route('webhook'));

        $response->assertOk();
    }

    /** @test */
    public function itStoresTheIncomingEvent()
    {
        $this->assertCount(0, Event::all());

        $this->postJson(route('webhook'), ['foo' => 'bar']);

        $this->assertCount(1, Event::all());
        $this->assertEquals('bar', Event::first()->data->foo);
    }
}
