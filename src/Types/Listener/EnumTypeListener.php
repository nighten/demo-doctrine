<?php

declare(strict_types=1);

namespace App\Types\Listener;

use App\Exception\DoctrineTypeException;
use App\Types\PhpEnumType;
use Doctrine\DBAL\Schema\Column;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class EnumTypeListener
{
    /**
     * @throws DoctrineTypeException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $eventArgs): void
    {
        $columns = [];

        foreach ($eventArgs->getSchema()->getTables() as $table) {
            foreach ($table->getColumns() as $column) {
                if ($column->getType() instanceof PhpEnumType) {
                    $columns[] = $column;
                }
            }
        }

        /** @var Column $column */
        foreach ($columns as $column) {
            /** @var PhpEnumType $type */
            $type = $column->getType();
            $enum = $type->getEnumClass();
            $cases = PhpEnumType::getCasesFromEnum($enum);
            $hash = md5(implode(',', $cases));
            $column->setComment(trim(sprintf(
                '%s (DC2Enum:%s)',
                $column->getComment(),
                $hash
            )));
        }
    }
}
