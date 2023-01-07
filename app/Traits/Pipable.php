<?php

namespace App\Traits;

use Illuminate\Pipeline\Pipeline;

trait Pipable
{
    public function pipeThrough(array $pipes): Pipeline
    {
        /** @var Pipeline $pipeline */
        $pipeline = app(Pipeline::class);

        return $pipeline->send($this)->through($pipes);
    }
}
