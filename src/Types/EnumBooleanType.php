<?php

namespace App\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

//Синтетический тип, для демонстрации. Bool лучше хранить в нативном типе в БД
class EnumBooleanType extends Type
{
    public const string STATUS_TRUE = 'Y';
    public const string STATUS_FALSE = 'N';

    public const string ENUM_BOOLEAN = 'enum_boolean';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "ENUM('Y', 'N')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?bool
    {
        if (is_null($value)) {
            return null;
        }
        return $value === self::STATUS_TRUE;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }
        return $value ? self::STATUS_TRUE : self::STATUS_FALSE;
    }

    public function getName(): string
    {
        return self::ENUM_BOOLEAN;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
