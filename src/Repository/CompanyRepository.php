<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends EntityRepository<Company>
 */
class CompanyRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Company::class),
        );
    }

    /**
     * @return Paginator<Company>
     */
    public function findWithPaginate(int $page, int $perPage): Paginator
    {
        $qb = $this->createQueryBuilder('Company');
        $qb->setFirstResult($perPage * ($page - 1));
        $qb->setMaxResults($perPage);
        return new Paginator($qb->getQuery());
    }
}
