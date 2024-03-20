<?php

declare(strict_types=1);

namespace App\Hydrator;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

class OneColumnIntegerHydrator extends AbstractHydrator
{
    /**
     * @throws Exception
     */
    protected function hydrateAllData(): array
    {
        return array_map('intval', $this->_stmt->fetchFirstColumn());
    }
}
