<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DocumentLocation\DocumentLocationIssueCity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<DocumentLocationIssueCity>
 */
class DocumentLocationIssueCityRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(DocumentLocationIssueCity::class),
        );
    }
}
