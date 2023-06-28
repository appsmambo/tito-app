<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function listen_to_replies()
    {
        $this->post('/api/webhook')
            ->assertStatus(200)
            ->assertSee('listenToReplies');
    }
}
