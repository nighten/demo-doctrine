<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Entity\UserSettings;
use App\Logger\DoctrineConsoleLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'example:one-to-one-relation-lazy',
    )
]
class OneToOneRelationLazyCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConfiguration()->setSQLLogger(new DoctrineConsoleLogger($output, true));
        $userRepository = $this->entityManager->getRepository(User::class);
        $userSettingsRepository = $this->entityManager->getRepository(UserSettings::class);

        $output->writeln('<info>Experiment 1</info>');

        //SELECT t0.id AS id_1, t0.name AS name_2, t0.active AS active_3, t0.settings_id AS settings_id_4 FROM user t0 WHERE t0.id = 1
        $user = $userRepository->find(1);

        //NO SQL query executed (lazy load | proxy object)
        $settingsFromUser = $user->getSettings();

        //SELECT t0.id AS id_1, t0.someSetting AS someSetting_2, t0.user_id AS user_id_3 FROM user_settings t0 WHERE t0.id = 1
        $someSettings = $settingsFromUser->getSomeSetting();

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 2</info>');

        //SELECT t0.id AS id_1, t0.someSetting AS someSetting_2, t0.user_id AS user_id_3 FROM user_settings t0 WHERE t0.user_id = 1 LIMIT 1
        $userSettings = $userSettingsRepository->findOneBy(['user' => 1]);
        //NO SQL query executed (lazy load | proxy object)
        $userFromSettings = $userSettings->getUser();

        //SELECT t0.id AS id_1, t0.name AS name_2, t0.active AS active_3, t0.settings_id AS settings_id_4 FROM user t0 WHERE t0.id = 1
        $name = $userFromSettings->getName();

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 3</info>');

        //SELECT u0_.id AS id_0, u0_.name AS name_1, u0_.active AS active_2, u0_.settings_id AS settings_id_3 FROM user u0_
        /** @var User[] $users */
        $users = $userRepository->createQueryBuilder('user')
            ->getQuery()->getResult();

        foreach ($users as $user) {
            $userSettings = $user->getSettings();
            //SELECT t0.id AS id_1, t0.someSetting AS someSetting_2, t0.user_id AS user_id_3 FROM user_settings t0 WHERE t0.id = ?
            $someSetting = $userSettings->getSomeSetting();
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 4</info>');

        //SELECT u0_.id AS id_0, u0_.name AS name_1, u0_.active AS active_2, u0_.settings_id AS settings_id_3
        //FROM user u0_ INNER JOIN user_settings u1_ ON u0_.settings_id = u1_.id
        /** @var User[] $users */
        $users = $userRepository->createQueryBuilder('user')
            ->join('user.userSettings', 'userSettings')
            ->getQuery()->getResult();

        foreach ($users as $user) {
            $userSettings = $user->getSettings();
            //SELECT t0.id AS id_1, t0.someSetting AS someSetting_2, t0.user_id AS user_id_3 FROM user_settings t0 WHERE t0.id = ?
            $someSetting = $userSettings->getSomeSetting();
        }

        $this->entityManager->clear();

        $output->writeln('<info>Experiment 5</info>');

        //SELECT u0_.id AS id_0, u0_.name AS name_1, u0_.active AS active_2, u1_.id AS id_3, u1_.someSetting AS someSetting_4, u0_.settings_id AS settings_id_5, u1_.user_id AS user_id_6
        //FROM user u0_ INNER JOIN user_settings u1_ ON u0_.settings_id = u1_.id
        /** @var User[] $users */
        $users = $userRepository->createQueryBuilder('user')
            ->select('user, userSettings')
            ->join('user.userSettings', 'userSettings')
            ->getQuery()->getResult();

        foreach ($users as $user) {
            $userSettings = $user->getSettings();
            $someSetting = $userSettings->getSomeSetting();
        }

        return Command::SUCCESS;
    }
}
