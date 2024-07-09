<?php

declare(strict_types=1);

namespace App\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Symfony\Component\Uid\UuidV4;

class UuidType extends GuidType
{
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return UuidV4::fromString($value);
    }
}
