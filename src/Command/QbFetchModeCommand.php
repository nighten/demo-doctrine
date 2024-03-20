<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Sprint;
use App\Entity\Task;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:qb-fetch-mode',
    )
]
class QbFetchModeCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('sprint FROM ' . Sprint::class . ' sprint');
        $query = $qb->getQuery();
        /** @var Sprint[] $sprints */
        //Тут будет запрос на все спринты
        $sprints = $query->getResult();
        foreach ($sprints as $sprint) {
            //Тут будет запрос на все таски спринта по id спринта
            foreach ($sprint->getTasks() as $task) {
                //Тут будет запрос на тип по ID типа (только к незагруженным)
                echo $task->getTitle() . ' type: ' . $task->getType()->getTitle() . PHP_EOL;
            }
        }

        $this->entityManager->getUnitOfWork()->clear();

        echo ' ------------------- ' . PHP_EOL;

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('sprint FROM ' . Sprint::class . ' sprint');
        $query = $qb->getQuery();
        //В данном случае ничего не изменится, т.к. связь ManyToMany не изменится
        //Подробнее в доке:
        //https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/dql-doctrine-query-language.html#temporarily-change-fetch-mode-in-dql
        $query->setFetchMode(Sprint::class, 'task', ClassMetadata::FETCH_EAGER);
        /** @var Sprint[] $sprints */
        $sprints = $query->getResult();
        foreach ($sprints as $sprint) {
            foreach ($sprint->getTasks() as $task) {
                echo $task->getTitle() . ' type: ' . $task->getType()->getTitle() . PHP_EOL;
            }
        }

        $this->entityManager->getUnitOfWork()->clear();

        echo ' ------------------- ' . PHP_EOL;

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('task FROM ' . Task::class . ' task');
        $query = $qb->getQuery();
        //$query->setFetchMode(Task::class, 'type', ClassMetadata::FETCH_EAGER);
        /** @var Task[] $tasks */
        //Тут будет запрос к таскам
        $tasks = $query->getResult();
        foreach ($tasks as $task) {
            //Тут будет запрос на тип по ID типа (только к незагруженным)
            echo $task->getTitle() . ' type: ' . $task->getType()->getTitle() . PHP_EOL;
        }

        $this->entityManager->getUnitOfWork()->clear();
        echo ' ------------------- ' . PHP_EOL;

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('task FROM ' . Task::class . ' task');
        $query = $qb->getQuery();
        $query->setFetchMode(Task::class, 'type', ClassMetadata::FETCH_EAGER);
        /** @var Task[] $tasks */
        //Тут будет запрос к таскам и сразу же запрос всех типов, которые нашлись у тасков
        $tasks = $query->getResult();
        foreach ($tasks as $task) {
            //Тут уже не будет доп запросов к таскам
            echo $task->getTitle() . ' type: ' . $task->getType()->getTitle() . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
