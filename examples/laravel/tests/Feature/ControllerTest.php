<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_requests_get_logged()
    {
        $this->getJson(route('log-request'))
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('logged_requests', ['id' => 1]);
    }

    public function test_image_gets_created()
    {
        $this->get(route('random-image'))
            ->assertSee('CREATOR: gd-jpeg');
    }

    public function test_healthcheck_works()
    {
        $this->get(route('healthcheck'))
            ->assertSee('OK');
    }
}
