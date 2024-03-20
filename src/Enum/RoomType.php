<?php

declare(strict_types=1);

namespace App\Enum;
enum RoomType: string
{
    case bedroom = 'BEDROOM';
    case kitchen = 'KITCHEN';
    case bathroom = 'BATHROOM';
}
