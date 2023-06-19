<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth(): void
    {
        $user = User::factory()->create();
        Model::factory()->count(1)->create(['user_id' => $user->id]);
        $this->get('/api/models')->assertStatus(302);
    }

    public function test_get_models(): void
    {
        $user = User::factory()->create();
        Model::factory()->count(1)->create(['user_id' => $user->id]);
        Model::factory()->count(1)->create(['user_id' => $user->id + 1]);

        $response = $this->actingAs($user)->get('/api/models')->assertOk();
        $this->assertEquals(1, count($response['models']), 'Only one model was returned');
        $this->assertEquals($response['models'][0]['id'], $user->id, 'Only the user\'s model was returned');
    }
}
