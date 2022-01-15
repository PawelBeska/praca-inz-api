<?php

namespace App\Services\Captcha;

use App\Exceptions\VerifyProviderNotFound;
use App\Interfaces\VerifyProviderInterface;
use App\Models\Service;
use App\Services\Captcha\Providers\TextProvider;

class Captcha
{

    private VerifyProviderInterface $verifyProvider;

    private Service $service;

    /**
     * @var array|string[]
     */
    public array $verifyProviders = [
        "text" => TextProvider::class
    ];

    /**
     * @param string|VerifyProviderInterface $verifyProvider
     * @param Service $service
     * @throws VerifyProviderNotFound
     */
    public function __construct(string|VerifyProviderInterface $verifyProvider, Service $service)
    {
        $this->service = $service;
        $this->setVerifyProvider($verifyProvider);
    }

    /**
     * @param string|VerifyProviderInterface $verifyProvider
     * @return void
     * @throws VerifyProviderNotFound
     */
    public function setVerifyProvider(string|VerifyProviderInterface $verifyProvider)
    {
        if ($verifyProvider instanceof VerifyProviderInterface)
            $this->verifyProvider = $verifyProvider;

        if (gettype($verifyProvider) == "string") {
            if (array_key_exists((string)$verifyProvider, $this->verifyProviders))
                $this->verifyProvider = new $this->verifyProviders[(string)$verifyProvider]($this->service);
            else
                throw new VerifyProviderNotFound('The specified provider was not found. Requested provider: ' . $verifyProvider);
        }
    }

    /**
     * @return VerifyProviderInterface
     */
    public function getVerifyProvider(): VerifyProviderInterface
    {
        return $this->verifyProvider;
    }


}
