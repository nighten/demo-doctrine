<?php

namespace App\Command;

use App\Entity\Company;
use App\Entity\User;
use App\Logger\DoctrineConsoleLogger;
use App\Logger\DoctrineLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:add-remove-inner-entity',
    )
]
class AddRemoveInnerEntityTestCommand extends Command
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

        $userRepository = $this->entityManager->getRepository(User::class);
        $companyRepository = $this->entityManager->getRepository(Company::class);
        /** @var User $user1 */
        $user1 = $userRepository->find(1);
        /** @var User $user2 */
        $user2 = $userRepository->find(2);
        /** @var User $user3 */
        $user3 = $userRepository->find(3);
        /** @var User $user4 */
        $user4 = $userRepository->find(4);
        /** @var Company $company1 */
        $company1 = $companyRepository->find(1);

        $company1->addUser($user1);
        $company1->removeUser($user1);

        $this->entityManager->flush();

        $company1->removeUser($user1);
        $company1->addUser($user1);
        $company1->addUser($user4);

        $this->entityManager->flush();

        return self::SUCCESS;
    }
}
