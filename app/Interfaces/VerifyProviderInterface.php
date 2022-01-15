<?php

namespace App\Interfaces;

use App\Models\Service;

interface  VerifyProviderInterface
{

    public function __construct(Service $service);


    public function verify();

    public function generate();

    public function __toString(): string;
}
