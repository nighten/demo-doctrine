<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Location;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-to-many-relation-remove',
    )
]
class OneToManyRelationRemoveCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));
        $locationRepository = $this->entityManager->getRepository(Location::class);
        $location = $locationRepository->find(1);
        if (!$location instanceof Location) {
            throw new Exception('Location with id = 1 not found. Execute "app:fixture-load" command before');
        }

        $urls = $location->getUrls();
        if (!array_key_exists(0, $urls)) {
            throw new Exception(
                'First LocationUrl from location with id = 1 not found. Execute "app:fixture-load" command before'
            );
        }
        $locationUrl1 = $location->getUrls()[0];

        if (!array_key_exists(1, $urls)) {
            throw new Exception(
                'Second LocationUrl from location with id = 1 not found. Execute "app:fixture-load" command before'
            );
        }
        $locationUrl2 = $location->getUrls()[1];

        $location->removeUrl($locationUrl1);
        $location->removeUrlByTitle($locationUrl2->getTitle());
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
