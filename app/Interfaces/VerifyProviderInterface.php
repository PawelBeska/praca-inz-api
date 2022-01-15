<?php

namespace App\Interfaces;

use App\Models\Service;
use App\Models\Verification;

interface  VerifyProviderInterface
{

    public function __construct(Service $service);


    public function verify(Verification $verification);

    public function generate();

    public function __toString(): string;
}
