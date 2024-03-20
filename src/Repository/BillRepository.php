<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Bill>
 */
class BillRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Bill::class),
        );
    }
}
