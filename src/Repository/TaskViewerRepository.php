<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaskViewer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<TaskViewer>
 */
class TaskViewerRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(TaskViewer::class),
        );
    }
}
