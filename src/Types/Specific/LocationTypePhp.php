<?php

namespace App\Types\Specific;

use App\Types\PhpEnumType;
use App\Enum\LocationType as LocationTypeEnum;

class LocationTypePhp extends PhpEnumType
{
    public const string CODE = 'location_type_enum';

    public function getName(): string
    {
        return self::CODE;
    }

    public function getEnumClass(): string
    {
        return LocationTypeEnum::class;
    }
}
