<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DocumentLocation\BaseDocumentLocation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<BaseDocumentLocation>
 */
class BaseDocumentLocationRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(BaseDocumentLocation::class),
        );
    }
}
