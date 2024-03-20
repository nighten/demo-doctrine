<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<UserGroup>
 */
class UserGroupRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(UserGroup::class),
        );
    }
}
