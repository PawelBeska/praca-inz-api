<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testGetProfile(): void
    {
        $this->get(route('dashboard.profile.index'))
            ->assertOk()
            ->assertJsonStructure([
                "message",
                'data' => [
                    'id',
                    'full_name',
                    'email',
                ],
                'status',
                'code'
            ]);
    }

    public function testUpdateProfile(): void
    {
        $oldPassword = $this->user->password;

        $data = [
            'full_name' => $this->faker->name(),
            'password' => 'password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ];

        $this->put(
            route('dashboard.profile.update', ['profile' => $this->user->id]),
            $data
        )
            ->assertOK()
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas(User::class, [
            'full_name' => $data['full_name'],
        ]);

        $this->assertDatabaseMissing(User::class, [
            'password' => $oldPassword,
        ]);
    }
}
