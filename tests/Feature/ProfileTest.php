<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    use WithFaker;

    protected User|Collection|Model $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }


    public function test_get_profile(): void
    {
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->get(route('dashboard.profile.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            'data' => [
                'id',
                'full_name',
                'email',
                'email_verified_at',
                'updated_at',
                'created_at'
            ],
            'status',
            'code'
        ]);
    }

    public function test_update_profile(): void
    {
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->put(route('dashboard.profile.update', ['profile' => $this->user->id]), [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'code',
            'message',
            'data'
        ]);


    }

    public function test_update_password(): void
    {
        Sanctum::actingAs(
            $this->user
        );
        $response = $this->put(route('dashboard.profile.update', ['profile' => $this->user->id]), [
            'password' => 'password', // password
            'new_password' => 'password', // password
            'new_password_confirmation' => 'password', // password
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'code',
            'message',
            'data'
        ]);
    }


}
