<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\Document2Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:no-inheritance-single-tsable',
    )
]
class NoInheritanceSingleTableCommand extends Command
{
    public function __construct(
        private readonly Document2Repository $documentRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $doc1 = $this->documentRepository->findOneBy(['title' => 'doc2_1']);
        $l1 = $doc1->getAllLocations();
        $l2 = $doc1->getLocations();
        $l3 = $doc1->getIssueCityLocations();
        return Command::SUCCESS;
    }
}
