<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<User>
 */
class UserRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(User::class),
        );
    }
}
