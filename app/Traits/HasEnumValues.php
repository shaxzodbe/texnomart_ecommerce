<?php

namespace App\Traits;

trait HasEnumValues
{
    public static function getAllCases(): array
    {
        return array_column(self::cases(), 'value');
    }
}
