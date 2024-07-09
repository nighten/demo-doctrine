<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\CompanyWithPrePersist;
use App\Logger\DoctrineConsoleLogger;
use App\Logger\DoctrineLogger;
use App\Repository\CompanyWithPrePersistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:update-on-pre-persist',
    )
]
class UpdateOnPrePersistCommand extends Command
{
    public function __construct(
        private readonly CompanyWithPrePersistRepository $companyRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DoctrineLogger $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->setOutput($output, true);
        $company1 = $this->companyRepository->find(1);
        $this->entityManager->flush();
        $company2 = $this->companyRepository->find(2);
        $company2->setName('new name 1');
        $this->entityManager->flush();

        #$companyNew = new CompanyWithPrePersist('test');
        #$this->entityManager->persist($companyNew);
        #$this->entityManager->flush();

        return Command::SUCCESS;
    }
}
