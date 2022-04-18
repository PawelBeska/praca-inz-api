<?php

namespace App\Services\Captcha;

use App\Enums\ServiceTypeEnum;
use App\Exceptions\VerifyProviderNotFound;
use App\Interfaces\VerifyProviderInterface;
use App\Models\Service;
use App\Services\Captcha\Providers\InvisibleProvider;
use App\Services\Captcha\Providers\TextProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Captcha
{

    private ServiceTypeEnum|VerifyProviderInterface $verifyProvider;

    private array|FormRequest|Request $request;
    private Service $service;

    /**
     * @param \App\Enums\ServiceTypeEnum|\App\Interfaces\VerifyProviderInterface $verifyProvider
     * @param Service $service
     * @param FormRequest|Request|array $request
     * @throws \App\Exceptions\VerifyProviderNotFound
     */
    public function __construct(ServiceTypeEnum|VerifyProviderInterface $verifyProvider, Service $service, array|FormRequest|Request $request)
    {
        $this->request = $request;
        $this->service = $service;
        $this->setVerifyProvider($verifyProvider);
    }

    /**
     * @return VerifyProviderInterface
     */
    public function getVerifyProvider(): VerifyProviderInterface
    {
        return $this->verifyProvider;
    }

    /**
     * @param \App\Enums\ServiceTypeEnum|\App\Interfaces\VerifyProviderInterface $verifyProvider
     * @return void
     * @throws \App\Exceptions\VerifyProviderNotFound
     */
    public function setVerifyProvider(ServiceTypeEnum|VerifyProviderInterface $verifyProvider): void
    {
        if ($verifyProvider instanceof VerifyProviderInterface) {
            $this->verifyProvider = $verifyProvider;
        } else {
            $this->verifyProvider = match ($verifyProvider->value) {
                ServiceTypeEnum::INVISIBLE->value => new InvisibleProvider($this->service, $this->request),
                ServiceTypeEnum::TEXT->value => new TextProvider($this->service, $this->request),
                default => throw new VerifyProviderNotFound('The specified provider was not found. Requested provider: ' . $verifyProvider)
            };
        }
    }


}
