<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CompanyWithPrePersist;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<CompanyWithPrePersist>
 */
class CompanyWithPrePersistRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(CompanyWithPrePersist::class),
        );
    }
}
