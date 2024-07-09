<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Company;
use App\Logger\DoctrineConsoleLogger;
use App\Logger\DoctrineLogger;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:table-name-in-qb',
    )
]
class TableNameInQbCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DoctrineLogger $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->setOutput($output, true);
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('count(company.id) as cnt')
            ->from(Company::class, 'company');
        $count = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        echo 'count = ' . $count . PHP_EOL;

        return Command::SUCCESS;
    }
}
