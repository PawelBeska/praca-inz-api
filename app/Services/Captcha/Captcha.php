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

    private VerifyProviderInterface $verifyProvider;

    private array|FormRequest|Request $request;
    private Service $service;

    /**
     * @param string|VerifyProviderInterface $verifyProvider
     * @param Service $service
     * @param FormRequest|Request|array $request
     * @throws VerifyProviderNotFound
     */
    public function __construct(string|VerifyProviderInterface $verifyProvider, Service $service, array|FormRequest|Request $request)
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
     * @param string|VerifyProviderInterface $verifyProvider
     * @return void
     * @throws VerifyProviderNotFound
     */
    public function setVerifyProvider(string|VerifyProviderInterface $verifyProvider): void
    {
        if ($verifyProvider instanceof VerifyProviderInterface) {
            $this->verifyProvider = $verifyProvider;
        } else {
            $this->verifyProvider = match ((string)$verifyProvider) {
                ServiceTypeEnum::INVISIBLE->value => new InvisibleProvider($this->service, $this->request),
                ServiceTypeEnum::TEXT->value => new TextProvider($this->service, $this->request),
                default => throw new VerifyProviderNotFound('The specified provider was not found. Requested provider: ' . $verifyProvider)
            };
        }
    }


}
