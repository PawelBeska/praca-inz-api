<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testLoginSuccess(): void
    {
        $this->post(
            route('login'),
            [
                'email' => $this->user->email,
                'password' => 'password'
            ]
        )->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'access_token',
                    'user' => [
                        'id',
                        'email',
                        'full_name',
                    ]
                ],
                'status',
                'code'
            ]);
    }

    public function testRegister(): void
    {
        Event::fake();
        $data = [
            'email' => $this->faker->email,
            'full_name' => $this->faker->name,
        ];

        $this->post(
            route('register'),
            [
                ...$data,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'code'
            ]);

        Event::assertDispatched(Registered::class);
    }

    public function testVerifySuccessEmail(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'user_activation_key' => Str::random(60)
        ]);

        $this->get(
            route('verifyEmail', ['token' => $user->user_activation_key]),
        )->assertOk()
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'code'
            ]);

        $this->assertDatabaseHas(User::class, [
            'user_activation_key' => null,
        ]);
    }

    public function testForgotPassword(): void
    {
        Queue::fake();
        /** @var User $user */
        $user = User::factory()->create();

        $this->post(
            route('passwordForgot'),
            [
                'email' => $user->email,
            ]
        )->assertOk()
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'code'
            ]);

        $this->assertDatabaseHas(User::class, [
            'user_activation_key' => null,
        ]);

        Queue::assertPushed(SendQueuedNotifications::class);
    }

    public function testResetPassword(): void
    {
        /** @var User $user */
        $user = User::factory()->create(
            [
                'password_reset_token' => Str::random(60),
                'password_reset_token_expires_at' => now()->addMinutes(30)
            ]
        );

        $this->post(
            route('passwordReset'),
            [
                'token' => $user->password_reset_token,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]
        )->assertOk()
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'code'
            ]);

    }
}
