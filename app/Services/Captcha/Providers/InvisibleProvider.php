<?php

namespace App\Services\Captcha\Providers;

use App\Enums\ServiceTypeEnum;
use App\Interfaces\VerifyProviderInterface;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\VerificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class InvisibleProvider implements VerifyProviderInterface
{


    public function __construct(private Service $service, private array|FormRequest|Request $request)
    {
    }

    public function verify(Verification $verification, array|FormRequest|Request $request): bool
    {


        if (
            !$verification->active ||
            $verification->service_id !== $this->service->id ||
            $request->ip() !== $verification->ip_address ||
            $verification->valid_until->isPast()) {
            Log::info('Captcha verification failed. Verification is not active, or the IP address is not the same as the one used for the verification, or the verification has expired.');
            return false;
        }
        Log::info('Captcha verification succeeded.');

        (new VerificationService($verification))->setActive(false);
        Log::info(Hash::check($request->get('answer'), $verification->control));
        return Hash::check($request->get('answer'), $verification->control);
    }


    /**
     * @return array
     * @throws \Exception
     */
    #[ArrayShape([
        'image' => "string",
        'token' => ["\Ramsey\Uuid\UuidInterface", null],
        'type' => "string"
    ])]
    public function generate(): array
    {
        $token = Str::uuid();

        $verification = (new VerificationService())->add(
            $this,
            $token,
            $this->service,
            $this->request->ip()
        );


        return [
            'type' => (string) $this,
            'token' => $verification->uuid,
            'access_token' => $token,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ServiceTypeEnum::INVISIBLE->value;
    }
}
