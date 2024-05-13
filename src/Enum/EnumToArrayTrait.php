<?php

namespace App\Enum;

trait EnumToArrayTrait
{
    public static function toArray(): array
    {
        $reflectionClass = new \ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }
}
