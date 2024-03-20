<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Task;
use App\Repository\SprintRepository;
use App\Repository\TaskRepository;
use App\Types\EnumBooleanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:filter-test',
    )
]
class TestFilterCommand extends Command
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly SprintRepository $sprintRepository,
        private readonly EntityManagerInterface $entityManager
        )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Without Filter
        $allTasks1 = $this->taskRepository->findAll();
        $output->writeln('[Without filter] All tasks from repository. Count = ' . count($allTasks1));

        $allTasks2 = $this->entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->getQuery()
            ->getResult();
        $output->writeln('[Without filter] All tasks from QB. count = ' . count($allTasks2));

        $query = $this->entityManager->createQuery('SELECT t FROM ' . Task::class . ' t');
        $allTasks3 = $query->getResult();
        $output->writeln('[Without filter] All tasks from DQL. count = ' . count($allTasks3));

        $sprint = $this->sprintRepository->find(1);
        $output->writeln('[Without filter] Sprint tasks from relation. count = ' . $sprint->getTasksCount());

        ### Enable Filter ###
        $filter = $this->entityManager->getFilters()->enable('active');
        $filter->setParameter('active', EnumBooleanType::STATUS_TRUE);
        $output->writeln('');
        $output->writeln('Enable Filter');

        //With Filter
        $activeTasks1 = $this->taskRepository->findAll();
        $output->writeln('[With filter] Active tasks 1 count = ' . count($activeTasks1));

        $activeTasks2 = $this->entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->getQuery()
            ->getResult();
        $output->writeln('[With filter] Active tasks 2 count = ' . count($activeTasks2));

        $activeTasks3 = $query->getResult();
        $output->writeln('[With filter] All tasks from DQL. count = ' . count($activeTasks3));

        $sprint = $this->sprintRepository->find(1);
        $output->writeln(
            '[With filter] Sprint tasks from relation without refresh. count = ' . $sprint->getTasksCount()
        );
        //!!! Если коллекция была ранее загружена по связи, то фильтры уже не применятся
        $this->entityManager->refresh($sprint);
        $output->writeln(
            '[With filter] Sprint tasks from relation after refresh. count = ' . $sprint->getTasksCount()
        );


        ### Dibale Filter ###
        $filter = $this->entityManager->getFilters()->disable('active');
        $output->writeln('');
        $output->writeln('Disable Filter');


        //Without Filter after disable
        $allTasks1 = $this->taskRepository->findAll();
        $output->writeln('[Without filter] All tasks from repository. Count = ' . count($allTasks1));

        $allTasks2 = $this->entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->getQuery()
            ->getResult();
        $output->writeln('[Without filter] All tasks from QB. count = ' . count($allTasks2));

        $query = $this->entityManager->createQuery('SELECT t FROM ' . Task::class . ' t');
        $allTasks3 = $query->getResult();
        $output->writeln('[Without filter] All tasks from DQL. count = ' . count($allTasks3));

        $sprint = $this->sprintRepository->find(1);
        $output->writeln('[Without filter] Sprint tasks from relation. count = ' . $sprint->getTasksCount());
        //!!! Если коллекция была ранее загружена по связи, то фильтры уже не применятся
        $this->entityManager->refresh($sprint);
        $output->writeln(
            '[Without filter] Sprint tasks from relation after refresh. count = ' . $sprint->getTasksCount()
        );

        return Command::SUCCESS;
    }
}
