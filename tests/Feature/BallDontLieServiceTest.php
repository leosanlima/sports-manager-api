<?php

namespace Tests\Feature;

use App\Facades\BallDontLie;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;


class BallDontLieServiceTest extends TestCase
{
    public function test_can_fetch_players_via_facade()
    {
        $player = BallDontLie::players(1, 1); 

        $this->assertNotEmpty($player['data']);
        $this->assertEquals(1, $player['data']['id']);
        $this->assertEquals('Alex', $player['data']['first_name']);
        $this->assertEquals('Abrines', $player['data']['last_name']);
    }
}
