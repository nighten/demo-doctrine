<?php

namespace App\Command;

use App\Entity\CompanyWithZeroLocation;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:company-with-zero-location',
    )
]
class CompanyWithZeroLocationCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws EntityNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $location = $this->entityManager->getRepository(Location::class)->find(1);

        $company = new CompanyWithZeroLocation('test company 1');
        $this->entityManager->persist($company);

        $company->setLocation($location);

        $this->entityManager->flush();

        echo $company->getLocation()->getTitle() . PHP_EOL;

        $company->setLocation(null);

        $this->entityManager->flush();

        /** @var CompanyWithZeroLocation $company1 */
        $company1 = $this->entityManager->getRepository(CompanyWithZeroLocation::class)->find(1);
        echo null === $company1->getLocation() ? 'Location is null' : 'Location is not null';
        echo PHP_EOL;

        /** @var CompanyWithZeroLocation $company2 */
        $company2 = $this->entityManager->getRepository(CompanyWithZeroLocation::class)->find(2);
        echo $company2->getLocation()->getTitle() . PHP_EOL;

        return self::SUCCESS;
    }
}
