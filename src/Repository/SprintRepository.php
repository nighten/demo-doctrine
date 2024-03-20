<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sprint;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Sprint>
 */
class SprintRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Sprint::class),
        );
    }
}
