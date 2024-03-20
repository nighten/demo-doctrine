<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DocumentLocation\DocumentLocation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<DocumentLocation>
 */
class DocumentLocationRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(DocumentLocation::class),
        );
    }
}
