<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Location>
 */
class LocationRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Location::class),
        );
    }
}
