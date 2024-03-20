<?php

namespace App\Types;

use App\Exception\DoctrineTypeException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class PhpEnumType extends Type
{
    /**
     * @throws DoctrineTypeException
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'ENUM(\'' . implode('\', \'', $this->getItemsFromEnumClass()) . '\')';
    }

    /**
     * @return string[]|int[]
     * @throws DoctrineTypeException
     */
    protected function getItemsFromEnumClass(): array
    {
        $enum = $this->getEnumClass();
        if (!enum_exists($enum)) {
            throw new DoctrineTypeException('Enum "' . $enum . '" not found');
        }
        return self::getCasesFromEnum($enum);
    }

    /**
     * @param class-string $enum
     * @return string[]|int[]
     * @throws DoctrineTypeException
     */
    public static function getCasesFromEnum(string $enum): array
    {
        $cases = [];
        foreach ($enum::cases() as $case) {
            $value = property_exists($case, 'value') ? $case->value : $case->name;
            if (str_contains($value, '\'')) {
                throw new DoctrineTypeException('Wrong symbol \' at case "' . $enum . ':' . $value . '"');
            }
            $cases[] = $value;
        }
        if (count($cases) === 0) {
            throw new DoctrineTypeException(
                'Empty enum cases is not allowed for doctrine enum type. Enum: "' . $enum . '"'
            );
        }
        return $cases;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string
     */
    abstract public function getEnumClass(): string;
}
