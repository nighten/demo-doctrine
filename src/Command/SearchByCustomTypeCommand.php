<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TaskRepository;
use App\Types\EnumBooleanType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:search-by-custom-type',
    )
]
class SearchByCustomTypeCommand extends Command
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //в процессе доработки, не окончательный вариант
        $result = $this->taskRepository
            ->createQueryBuilder('task')
            ->where('task.active = :active')
            ->setParameter('active', EnumBooleanType::STATUS_TRUE)
            ->getQuery()
            ->getResult();
        $output->writeln('done');
        return Command::SUCCESS;
    }
}
