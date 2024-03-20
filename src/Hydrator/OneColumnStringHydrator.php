<?php

declare(strict_types=1);

namespace App\Hydrator;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

class OneColumnStringHydrator extends AbstractHydrator
{
    /**
     * @throws Exception
     */
    protected function hydrateAllData(): array
    {
        return $this->_stmt->fetchFirstColumn();
    }
}
