<?php

namespace App\Interfaces;

use App\Models\Service;
use App\Models\Verification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface  VerifyProviderInterface
{

    public function __construct(Service $service,Array|FormRequest|Request $request);


    public function verify(Verification $verification, Array|FormRequest|Request $request);

    public function generate();

    public function __toString(): string;
}
