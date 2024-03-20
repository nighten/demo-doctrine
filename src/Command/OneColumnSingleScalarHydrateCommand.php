<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-column-single-scalar-hydrate',
    )
]
class OneColumnSingleScalarHydrateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));

        $userRepository = $this->entityManager->getRepository(User::class);

        $userName = $userRepository->createQueryBuilder('user')
            ->select('user.name')
            ->where('user.id = :userId')
            ->andWhere('user.active = :active')
            ->setParameter('userId', 1)
            ->setParameter('active', true)
            ->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        return Command::SUCCESS;
    }
}
