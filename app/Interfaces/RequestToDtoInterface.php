<?php

namespace App\Interfaces;

interface RequestToDtoInterface
{
    public function toDto(): DtoInterface;
}
