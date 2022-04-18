<?php

namespace Tests\Feature;

use App\Enums\ServiceTypeEnum;
use App\Models\Service;
use App\Models\User;
use App\Services\Captcha\VerificationService;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CaptchaTest extends TestCase
{

    public function test_generate_invisibility_captcha(): void
    {
        $user = User::factory()
            ->has(Service::factory()->type(ServiceTypeEnum::INVISIBLE)->count(1))
            ->createOne();
        Sanctum::actingAs(
            $user
        );

        $response = $this->post(
            route("captcha.generate", ['service' => $user->services()->first()->uuid])
        );


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'type',
                "access_token",
                "token"
            ],
            'status',
            'code'
        ]);


    }

    public function test_verify_invisibility_captcha(): void
    {
        $user = User::factory()
            ->has(Service::factory()->type(ServiceTypeEnum::INVISIBLE)->count(1))
            ->createOne();
        Sanctum::actingAs(
            $user
        );


        $response = $this->post(
            route("captcha.generate", ['service' => $user->services()->first()->uuid])
        );
        $data = $response->json()['data'];


        $response = $this->post(
            route('captcha.verify', ['service' => $user->services()->first()->uuid, 'verification' => $data['token']])
        );


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'code'
        ]);
    }

    public function test_generate_text_captcha(): void
    {
        $user = User::factory()
            ->has(Service::factory()->type(ServiceTypeEnum::TEXT)->count(1))
            ->createOne();
        Sanctum::actingAs(
            $user
        );

        $response = $this->post(
            route("captcha.generate", ['service' => $user->services()->first()->uuid])
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                "image",
                "token"
            ],
            'status',
            'code'
        ]);
    }

    public function test_verify_text_captcha(): void
    {

        $user = User::factory()
            ->has(Service::factory()->type(ServiceTypeEnum::TEXT)->count(1))
            ->createOne();
        Sanctum::actingAs(
            $user
        );


        $verification = (new VerificationService())->add(
            ServiceTypeEnum::TEXT->value,
            "test",
            $user->services()->first(),
            "127.0.0.1"
        );

        $response = $this->post(
            route('captcha.verify', [
                'service' => $user->services()->first()->uuid,
                'verification' => $verification->uuid,
                'answer' => "test"
            ])
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "data",
            "status",
            "code"
        ]);
    }

}
