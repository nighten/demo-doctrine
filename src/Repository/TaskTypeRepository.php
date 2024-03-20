<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaskType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<TaskType>
 */
class TaskTypeRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(TaskType::class),
        );
    }
}
