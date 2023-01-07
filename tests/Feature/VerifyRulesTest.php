<?php

namespace Tests\Feature;

use App\Dto\CaptchaVerificationDto;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\VerifyRules\ActiveRule;
use App\Services\Captcha\VerifyRules\HashRule;
use App\Services\Captcha\VerifyRules\IpAddressRule;
use App\Services\Captcha\VerifyRules\NotExpiredRule;
use App\Services\Captcha\VerifyRules\PrivateKeyRule;
use App\Services\Captcha\VerifyRules\ServiceIdRule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifyRulesTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;


    public function setUp(): void
    {
        parent::setUp();

        $this->service = Service::factory()->create();
    }

    public function testActiveVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                ActiveRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testActiveVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => false
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
                ActiveRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }

    public function testHashVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                HashRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testHashVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'invalid'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
                HashRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }

    public function testIpAddressVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                IpAddressRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testIpAddressVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '1.1.1.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
                IpAddressRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }

    public function testNotExpiredVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true,
                            'valid_until' => now()->addMinutes(5)
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                NotExpiredRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testNotExpiredVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true,
                            'valid_until' => Carbon::now()->subMinute()
                        ]
                    ),
                'ipAddress' => '1.1.1.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
                NotExpiredRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }

    public function testPrivateKeyVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                PrivateKeyRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testPrivateKeyVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'invalid',
                'answer' => 'test'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
               PrivateKeyRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }

    public function testServiceIdVerifyRuleWhenTrue(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => $this->service->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = (bool) $captchaVerificationDto->pipeThrough(
            [
                ServiceIdRule::class
            ]
        )->thenReturn();

        $this->assertTrue($validation);
    }

    public function testServiceIdVerifyRuleWhenFalse(): void
    {
        /** @var CaptchaVerificationDto $captchaVerificationDto */
        $captchaVerificationDto = app(
            CaptchaVerificationDto::class,
            [
                'service' => $this->service,
                'verification' => Verification::factory()
                    ->for($this->service, 'service')
                    ->create(
                        [
                            'service_id' => Service::factory()->create()->id,
                            'active' => true
                        ]
                    ),
                'ipAddress' => '127.0.0.1',
                'privateKey' => 'test',
                'answer' => 'test'
            ]
        );

        $validation = $captchaVerificationDto->pipeThrough(
            [
                ServiceIdRule::class
            ]
        )->thenReturn();

        $this->assertFalse($validation);
    }
}
