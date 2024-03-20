<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\FlatTaskDto;
use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:dql-fields-and-dto-mapping',
    )
]
class DqlFieldsAndDtoMappingCommand extends Command
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $list = $this->taskRepository->createQueryBuilder('task')
            ->select(
                'NEW ' . FlatTaskDto::class
                . '(task.id, task.title, assignee.name, type.title, component.title)'
            )
            ->leftJoin('task.assignee', 'assignee')
            ->innerJoin('task.type', 'type')
            ->innerJoin('task.components', 'component')
            ->getQuery()
            ->getResult();
        return Command::SUCCESS;
    }
}
