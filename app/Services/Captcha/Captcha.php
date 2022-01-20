<?php

namespace App\Services\Captcha;

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

    private Array|FormRequest|Request $request;
    private Service $service;

    /**
     * @var array|string[]
     */
    public array $verifyProviders = [
        "text" => TextProvider::class,
        "invisible"=> InvisibleProvider::class
    ];

    /**
     * @param string|VerifyProviderInterface $verifyProvider
     * @param Service $service
     * @param FormRequest|Request|array $request
     * @throws VerifyProviderNotFound
     */
    public function __construct(string|VerifyProviderInterface $verifyProvider, Service $service, Array|FormRequest|Request $request)
    {
        $this->request = $request;
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
                $this->verifyProvider = new $this->verifyProviders[(string)$verifyProvider]($this->service, $this->request);
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
