<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DocumentLocationWithoutDoctrineDiscriminator\Document;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Document>
 */
class Document2Repository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Document::class),
        );
    }
}
