<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\CompanyRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:qb-paginator',
    )
]
class QueryBuilderPaginatorCommand extends Command
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,
    ) {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $companyPaginator = $this->companyRepository->findWithPaginate(2, 2);
        echo 'count: ' . $companyPaginator->count() . PHP_EOL;
        foreach ($companyPaginator as $company) {
            echo $company->getName() . PHP_EOL;
        }
        return Command::SUCCESS;
    }
}
