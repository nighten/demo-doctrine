<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Location;
use App\Enum\LocationType;
use App\Enum\RoomType;
use App\Logger\DoctrineConsoleLogger;
use App\Logger\DoctrineLogger;
use App\Repository\LocationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:work-with-enums',
    )
]
class TestEnumsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LocationRepository $locationRepository,
        private readonly RoomRepository $roomRepository,
        private readonly DoctrineLogger $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->setOutput($output, true);

        $location1 = $this->locationRepository->find(1);

        $output->writeln('--- --- ---');
        $output->writeln($location1->getArea1() ?? '"null"');
        $output->writeln($location1->getArea2() ?? '"null"');
        $output->writeln($location1->getArea3());
        $output->writeln($location1->getArea4());
        $output->writeln('--- +++ ---');
        $output->writeln($location1->getType1()?->value ?? '"null"');
        $output->writeln($location1->getType2()?->value ?? '"null"');
        $output->writeln($location1->getType3()->value);
        $output->writeln($location1->getType4()->value);
        $output->writeln('--- --- ---');

        $location1->setArea1('new');
        $location1->setArea2('new');
        $location1->setArea3('new');
        $location1->setArea4('new');

        $location1->setType1(LocationType::town);
        $location1->setType2(LocationType::town);
        $location1->setType3(LocationType::town);
        $location1->setType4(LocationType::town);

        $this->entityManager->flush();
        $this->entityManager->refresh($location1);

        $output->writeln('--- --- ---');
        $output->writeln($location1->getArea1() ?? '"null"');
        $output->writeln($location1->getArea2() ?? '"null"');
        $output->writeln($location1->getArea3());
        $output->writeln($location1->getArea4());
        $output->writeln('--- +++ ---');
        $output->writeln($location1->getType1()?->value ?? '"null"');
        $output->writeln($location1->getType2()?->value ?? '"null"');
        $output->writeln($location1->getType3()->value);
        $output->writeln($location1->getType4()->value);
        $output->writeln('--- --- ---');

        $output->writeln('--- === --- === ---');

        $room1 = $this->roomRepository->find(1);

        $output->writeln('--- --- ---');
        $output->writeln($room1->getType1()?->value ?? '"null"');
        $output->writeln($room1->getType2()?->value ?? '"null"');
        $output->writeln($room1->getType3()->value);
        $output->writeln($room1->getType4()->value);
        $output->writeln('--- --- ---');

        $room1->setType1(RoomType::kitchen);
        $room1->setType2(RoomType::kitchen);
        $room1->setType3(RoomType::kitchen);
        $room1->setType4(RoomType::kitchen);

        $this->entityManager->flush();
        $this->entityManager->refresh($room1);

        $output->writeln('--- --- ---');
        $output->writeln($room1->getType1()?->value ?? '"null"');
        $output->writeln($room1->getType2()?->value ?? '"null"');
        $output->writeln($room1->getType3()->value);
        $output->writeln($room1->getType4()->value);
        $output->writeln('--- --- ---');

        return Command::SUCCESS;
    }
}
