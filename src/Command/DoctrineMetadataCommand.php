<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use ReflectionException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:doctrine-metadata',
    )
]
class DoctrineMetadataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws ReflectionException
     * @throws MappingException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $metadataFactory = $this->entityManager->getMetadataFactory();
        $metadataCompany = $metadataFactory->getMetadataFor(Company::class);

        echo 'Table name for ' . Company::class . ' = ' . $metadataCompany->table['name'] . PHP_EOL;

        return Command::SUCCESS;
    }
}
