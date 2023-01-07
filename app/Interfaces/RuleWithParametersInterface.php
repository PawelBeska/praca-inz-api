<?php

namespace App\Interfaces;

interface RuleWithParametersInterface
{
    public function setParameter(mixed $parameter): static;
}