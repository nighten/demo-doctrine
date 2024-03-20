<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Bill;
use App\Entity\Account;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-to-one-relation-eager',
    )
]
class OneToOneRelationEagerCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));
        $billRepository = $this->entityManager->getRepository(Bill::class);
        $accountRepository = $this->entityManager->getRepository(Account::class);

        $output->writeln('<info>Experiment 1</info>');

        //SELECT t0.id AS id_1, t0.name AS name_2, t0.account_id AS account_id_3 FROM bill t0 WHERE t0.id = ?
        $bill = $billRepository->find(1);

        //NO SQL query executed (lazy load | proxy object)
        $account = $bill->getAccount();

        //SELECT t0.id AS id_1, t0.name AS name_2, t3.id AS id_4, t3.name AS name_5, t3.account_id AS account_id_6
        //FROM account t0 LEFT JOIN bill t3 ON t3.account_id = t0.id WHERE t0.id = ?
        $accountName = $account->getName();

        $output->writeln('<info>Experiment 2</info>');

        //SELECT t0.id AS id_1, t0.name AS name_2, t3.id AS id_4, t3.name AS name_5, t3.account_id AS account_id_6
        //FROM account t0 LEFT JOIN bill t3 ON t3.account_id = t0.id WHERE t0.id = ?
        $account = $accountRepository->find(1);

        //NO SQL query executed (object already loaded)
        $bill = $account->getBill();

        //NO SQL query executed (object already loaded)
        $billName = $bill->getName();


        $this->entityManager->clear();

        $output->writeln('<info>Experiment 3 (bill)</info>');

        //SELECT p0_.id AS id_0, p0_.name AS name_1, p0_.account_id AS account_id_2 FROM bill p0_
        /** @var Bill[] $bills */
        $bills = $billRepository->createQueryBuilder('bill')
            ->getQuery()->getResult();

        foreach ($bills as $bill) {
            $account = $bill->getAccount();
            if (null !== $account) {
                //SELECT t0.id AS id_1, t0.name AS name_2, t3.id AS id_4, t3.name AS name_5, t3.account_id AS account_id_6
                //FROM account t0 LEFT JOIN bill t3 ON t3.account_id = t0.id WHERE t0.id = ?
                $accountName = $account->getName();
            }
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 4 (bill)</info>');

        //SELECT p0_.id AS id_0, p0_.name AS name_1, p0_.account_id AS account_id_2
        //FROM bill p0_ INNER JOIN account t1_ ON p0_.account_id = t1_.id
        /** @var Bill[] $bills */
        $bills = $billRepository->createQueryBuilder('bill')
            ->join('bill.account', 'account')
            ->getQuery()->getResult();

        foreach ($bills as $bill) {
            $account = $bill->getAccount();
            if (null !== $account) {
                //SELECT t0.id AS id_1, t0.name AS name_2, t3.id AS id_4, t3.name AS name_5, t3.account_id AS account_id_6
                //FROM account t0 LEFT JOIN bill t3 ON t3.account_id = t0.id WHERE t0.id = ?
                $accountName = $account->getName();
            }
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 5 (bill)</info>');

        //SELECT p0_.id AS id_0, p0_.name AS name_1, t1_.id AS id_2, t1_.name AS name_3, p0_.account_id AS account_id_4
        //FROM bill p0_ INNER JOIN account t1_ ON p0_.account_id = t1_.id
        /** @var Bill[] $bills */
        $bills = $billRepository->createQueryBuilder('bill')
            ->select('bill, account')
            ->join('bill.account', 'account')
            ->getQuery()->getResult();

        foreach ($bills as $bill) {
            $account = $bill->getAccount();
            if (null !== $account) {
                //No SQL query executed
                $accountName = $account->getName();
            }
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 6 (account)</info>');

        //SELECT t0_.id AS id_0, t0_.name AS name_1 FROM account t0_
        //!!!По каждому найденному треку!!!
        //SELECT t0.id AS id_1, t0.name AS name_2, t0.account_id AS account_id_3 FROM bill t0 WHERE t0.account_id = ?
        /** @var Account[] $accounts */
        $accounts = $accountRepository->createQueryBuilder('account')
            ->getQuery()->getResult();

        foreach ($accounts as $account) {
            $bill = $account->getBill();
            if (null !== $bill) {
                //No SQL query executed
                $bill->getName();
            }
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 7 (account)</info>');

        //SELECT t0_.id AS id_0, t0_.name AS name_1
        //FROM account t0_ INNER JOIN bill p1_ ON t0_.id = p1_.account_id
        //!!!По каждому найденному треку!!! (тут меньше чем в предыдущем может быть, т.к. INNER JOIN)
        //SELECT t0.id AS id_1, t0.name AS name_2, t0.account_id AS account_id_3 FROM bill t0 WHERE t0.account_id = ?
        /** @var Account[] $accounts */
        $accounts = $accountRepository->createQueryBuilder('account')
            ->join('account.bill', 'bill')
            ->getQuery()->getResult();

        foreach ($accounts as $account) {
            $bill = $account->getBill();
            if (null !== $bill) {
                //No SQL query executed
                $bill->getName();
            }
        }

        return Command::SUCCESS;
    }
}
