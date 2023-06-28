<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebHookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function listen_to_replies()
    {
        $this->post('/api/webhook')
            ->assertStatus(200)
            ->assertSee('listenToReplies');
    }

    public function validate_reniec()
    {
        $this->post('/api/validate_reniec')
            ->data('{"data":"09257148"}')
            ->assertStatus(200)
            ->assertSee('listenToReplies');
    }

}
