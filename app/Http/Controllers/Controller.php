<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function reportError(Throwable $e)
    {
        Log::error(
            $e->getMessage()
            . PHP_EOL . 'IN LINE: ' . $e->getLine()
            . PHP_EOL . 'IN FILE: ' . $e->getFile()
            . PHP_EOL . 'Message: ' . $e->getMessage()
        );
    }
}
