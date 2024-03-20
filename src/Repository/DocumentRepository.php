<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DocumentLocation\Document;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Document>
 */
class DocumentRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Document::class),
        );
    }
}
