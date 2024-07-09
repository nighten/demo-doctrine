<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Entity\UserCalendarSettings;
use App\Logger\DoctrineConsoleLogger;
use App\Logger\DoctrineLogger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-to-one-left-join',
    )
]
class OneToOneLeftJoinCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DoctrineLogger $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->setOutput($output, true);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u.name, calendarSettings.enabled')
            ->from(User::class, 'u')
            ->leftJoin(
                UserCalendarSettings::class,
                'calendarSettings',
                Join::WITH,
                'u = calendarSettings.user',
            );

        //SELECT u0_.name AS name_0, u1_.enabled AS enabled_1 FROM user u0_ LEFT JOIN user_calendar_settings u1_ ON (u0_.id = u1_.user_id)
        $result = $qb->getQuery()->getResult();

        return Command::SUCCESS;
    }
}
