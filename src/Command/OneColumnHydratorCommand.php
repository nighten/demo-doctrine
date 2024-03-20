<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Task;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-column-hydrator',
    )
]
class OneColumnHydratorCommand extends Command
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
        $qb->select(
            'task.id FROM ' . Task::class .
            ' task WHERE task.title IN (\'Task 4\', \'Task 3\', \'Task 5\')'
        );

        /* array(3) {
              [0]=>
              array(1) {
                ["id"]=>
                int(3)
              }
              [1]=>
              array(1) {
                ["id"]=>
                int(4)
              }
              [2]=>
              array(1) {
                ["id"]=>
                int(5)
              }
            }
         */
        $result = $qb->getQuery()->getResult();

        /* array(3) {
              [0]=>
              string(1) "3"
              [1]=>
              string(1) "4"
              [2]=>
              string(1) "5"
            }
         */
        $result1 = $qb->getQuery()->getResult('OneColumnStringHydrator');

        /* array(3) {
              [0]=>
              int(3)
              [1]=>
              int(4)
              [2]=>
              int(5)
            }
         */
        $result2 = $qb->getQuery()->getResult('OneColumnIntegerHydrator');

        return Command::SUCCESS;
    }
}
