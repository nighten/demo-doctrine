<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Account>
 */
class AccountRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Account::class),
        );
    }
}
