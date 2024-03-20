<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Component;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Component>
 */
class ComponentRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Component::class),
        );
    }
}
