<?php

namespace App\Command;

use App\Entity\UuidV4Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'uuid:test',
    )
]
class UuidTestCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $qb = $this->entityManager->getRepository(UuidV4Entity::class)->createQueryBuilder('ue');
        $res = $qb->getQuery()->getResult();
        var_dump($res);

        return self::SUCCESS;
    }
}
