<?php

namespace App\Exceptions;

use Exception;

class VerifyProviderNotFound extends Exception
{
    public function __construct(string $verifyProvider)
    {
        parent::__construct("Verify provider {$verifyProvider} not found");
    }
}
