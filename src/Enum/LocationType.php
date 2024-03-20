<?php

declare(strict_types=1);

namespace App\Enum;

enum LocationType: string
{
    case country = 'country';
    case region = 'region';
    case city = 'city';
    case town = 'town';
}
