<?php

declare(strict_types=1);

namespace App\Filters;

use App\Interfaces\ActiveEnumAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ActiveFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        // Check if the entity implements the ActiveAware interface
        if ($targetEntity->reflClass->implementsInterface(ActiveEnumAware::class)) {
            // getParameter applies quoting automatically
            return $targetTableAlias . '.active = ' . $this->getParameter('active');
        }

        return '';
    }
}
