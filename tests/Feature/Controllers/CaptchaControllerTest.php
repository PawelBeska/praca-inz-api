<?php

namespace Tests\Feature\Controllers;

use App\Enums\VerificationTypeEnum;
use App\Models\Service;
use App\Models\User;
use App\Models\Verification;
use App\Services\Captcha\VerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CaptchaControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    protected User $user;

    protected Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->service = Service::factory()
            ->for($this->user, 'user')
            ->create();

        $this->actingAs($this->user);
    }

    public function testGenerateInvisibilityCaptcha(): void
    {
        Config::set('captcha.invisible.max_attempts', 5);

        $this->post(
            route('captcha.generate', ['service' => $this->service->id])
        )
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'type',
                    'access_token',
                    'verification_id'
                ],
                'status',
                'code'
            ]);

        $this->assertDatabaseCount(Verification::class, 1);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 1,
                'ip_address' => '127.0.0.1',
                'type' => VerificationTypeEnum::INVISIBLE->value,
            ]
        );
    }

    public function testSuccessVerifyInvisibilityCaptcha(): void
    {
        /** @var Verification $verification */
        $verification = Verification::factory()
            ->for($this->service, 'service')
            ->create(
                [
                    'type' => VerificationTypeEnum::INVISIBLE->value,
                ]
            );

        $this->post(
            route(
                'captcha.verify',
                [
                    'service' => $this->service->id,
                    'verification' => $verification->id
                ]
            ),
            [
                'private_key' => Crypt::decrypt($this->service->private_key),
                'answer' => 'test'
            ]
        )
            ->assertOk()
            ->assertJsonStructure(
                [
                    'message',
                    'data',
                    'status',
                    'code'
                ]
            )
            ->assertJsonFragment([
                'code' => 200,
                'status' => 'ok',
                'data'=> true
            ]);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 0
            ]
        );
    }

    public function testFailedVerifyInvisibilityCaptcha(): void
    {
        /** @var Verification $verification */
        $verification = Verification::factory()
            ->for($this->service, 'service')
            ->create(
                [
                    'type' => VerificationTypeEnum::INVISIBLE->value,
                    'ip_address' => '1.1.1.1'
                ]
            );

        $this->post(
            route(
                'captcha.verify',
                [
                    'service' => $this->service->id,
                    'verification' => $verification->id
                ]
            ),
            [
                'private_key' => Crypt::decrypt($this->service->private_key),
                'answer' => 'test'
            ]
        )
            ->assertOk()
            ->assertJsonStructure(
                [
                    'message',
                    'data',
                    'status',
                    'code'
                ]
            )
            ->assertJsonFragment([
                'code' => 200,
                'status' => 'ok',
                'data'=> false
            ]);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 0
            ]
        );
    }

    public function testGenerateTextCaptcha(): void
    {
        Config::set('captcha.invisible.max_attempts', 0);
        Config::set('captcha.text.max_attempts', 5);

        $this->post(
            route('captcha.generate', ['service' => $this->service->id])
        )->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'type',
                    'image',
                    'verification_id'
                ],
                'status',
                'code'
            ]);

        $this->assertDatabaseCount(Verification::class, 1);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 1,
                'ip_address' => '127.0.0.1',
                'type' => VerificationTypeEnum::TEXT->value,
            ]
        );
    }

    public function testSuccessVerifyTextCaptcha(): void
    {
        /** @var Verification $verification */
        $verification = Verification::factory()
            ->for($this->service, 'service')
            ->create(
                [
                    'type' => VerificationTypeEnum::TEXT->value,
                ]
            );

        $this->post(
            route(
                'captcha.verify',
                [
                    'service' => $this->service->id,
                    'verification' => $verification->id
                ]
            ),
            [
                'private_key' => Crypt::decrypt($this->service->private_key),
                'answer' => 'test'
            ]
        )
            ->assertOk()
            ->assertJsonStructure(
                [
                    'message',
                    'data',
                    'status',
                    'code'
                ]
            )
            ->assertJsonFragment([
                'code' => 200,
                'status' => 'ok',
                'data'=> true
            ]);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 0
            ]
        );
    }

    public function testFailedVerifyTextCaptcha(): void
    {
        /** @var Verification $verification */
        $verification = Verification::factory()
            ->for($this->service, 'service')
            ->create(
                [
                    'type' => VerificationTypeEnum::TEXT->value,
                    'ip_address' => '1.1.1.1'
                ]
            );

        $this->post(
            route(
                'captcha.verify',
                [
                    'service' => $this->service->id,
                    'verification' => $verification->id
                ]
            ),
            [
                'private_key' => Crypt::decrypt($this->service->private_key),
                'answer' => 'test'
            ]
        )->assertOk()
            ->assertJsonStructure(
                [
                    'message',
                    'data',
                    'status',
                    'code'
                ]
            )
            ->assertJsonFragment([
                'code' => 200,
                'status' => 'ok',
                'data'=> false
            ]);

        $this->assertDatabaseHas(Verification::class, [
                'service_id' => $this->service->id,
                'active' => 0
            ]
        );
    }
}
