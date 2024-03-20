<?php

declare(strict_types=1);

namespace App\Command;

use App\Logger\DoctrineConsoleLogger;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:many-to-many-bidirectional',
    )
]
class ManyToManyBidirectionalTestCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly RoomRepository $roomRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));

        //SELECT t0.id AS id_1, t0.name AS name_2, t0.active AS active_3, t0.settings_id AS settings_id_4 FROM user t0 WHERE t0.id = ?
        $user1 = $this->userRepository->find(1);
        //SELECT t0.id AS id_1, t0.name AS name_2 FROM room t0 INNER JOIN users_rooms_custom ON t0.id = users_rooms_custom.room_id WHERE users_rooms_custom.user_id = ?
        //1 room1
        //2 room2
        //3 room3
        foreach ($user1->getRooms() as $room) {
            $output->writeln($room->getId() . ' ' . $room->getName());
        }

        $output->writeln('');

        //SELECT t0.id AS id_1, t0.name AS name_2 FROM room t0 WHERE t0.id = ?
        $room4 = $this->roomRepository->find(4);
        //SELECT t0.id AS id_1, t0.name AS name_2, t0.active AS active_3, t0.settings_id AS settings_id_4 FROM user t0 INNER JOIN users_rooms_custom ON t0.id = users_rooms_custom.user_id WHERE users_rooms_custom.room_id = ?
        //3 User 3
        //4 User 4
        foreach ($room4->getUsers() as $user) {
            $output->writeln($user->getId() . ' ' . $user->getName());
        }

        return Command::SUCCESS;
    }
}
