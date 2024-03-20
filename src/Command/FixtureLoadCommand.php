<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Company;
use App\Entity\CompanyWithPrePersist;
use App\Entity\CompanyWithZeroLocation;
use App\Entity\Component;
use App\Entity\DocumentLocation\Document;
use App\Entity\DocumentLocationWithoutDoctrineDiscriminator\Document as DocumentType2;
use App\Entity\Location;
use App\Entity\LocationUrl;
use App\Entity\Bill;
use App\Entity\Room;
use App\Entity\Sprint;
use App\Entity\Task;
use App\Entity\TaskType;
use App\Entity\Account;
use App\Entity\User;
use App\Entity\UserCalendarSettings;
use App\Entity\UuidV4Entity;
use App\Enum\LocationType;
use App\Enum\RoomType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'app:fixture-load',
    )
]
class FixtureLoadCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clear();

        $user1 = new User('User 1');
        $user2 = new User('User 2');
        $user3 = new User('User 3');
        $user4 = new User('User 4');

        $taskTypeIssue = new TaskType('Issue');
        $taskTypeBug = new TaskType('Bug');
        $taskTypeResearch = new TaskType('Research');
        $task1 = new Task('Task 1', $taskTypeIssue);
        $task2 = new Task('Task 2', $taskTypeIssue);
        $task3 = new Task('Task 3', $taskTypeBug);
        $task4 = new Task('Task 4', $taskTypeBug);
        $task5 = new Task('Task 5', $taskTypeResearch);
        $task6 = new Task('Task 6', $taskTypeResearch);
        $task7 = new Task('Task 7', $taskTypeResearch);
        $task7->setActive(false);
        $task8 = new Task('Task 8', $taskTypeResearch);
        $task9 = new Task('Task 9', $taskTypeResearch);
        $task9->setActive(false);

        $componentBill = new Component('Bill');
        $componentHome = new Component('Home');
        $componentPhpunit = new Component('Phpunit');
        $componentAgenda = new Component('Agenda');

        $task1->addComponents($componentBill, $componentHome);
        $task2->addComponent($componentHome);
        $task2->addComponent($componentPhpunit);

        $sprint1 = new Sprint('Sprint 1');
        $sprint2 = new Sprint('Sprint 2');
        $sprint3 = new Sprint('Sprint 3');

        $task1->addToSprint($sprint1);
        $task5->addToSprint($sprint1);
        $task6->addToSprint($sprint1);
        $task9->addToSprint($sprint1);
        $sprint2->addTask($task2);
        $sprint2->addTask($task3);
        $sprint2->addTask($task4);

        $this->entityManager->persist($user1);
        $this->entityManager->persist($user2);
        $this->entityManager->persist($user3);
        $this->entityManager->persist($user4);
        $this->entityManager->persist($taskTypeIssue);
        $this->entityManager->persist($taskTypeBug);
        $this->entityManager->persist($taskTypeResearch);
        $this->entityManager->persist($componentBill);
        $this->entityManager->persist($componentHome);
        $this->entityManager->persist($componentPhpunit);
        $this->entityManager->persist($componentAgenda);
        $this->entityManager->persist($sprint1);
        $this->entityManager->persist($sprint2);
        $this->entityManager->persist($sprint3);
        $this->entityManager->persist($task1);
        $this->entityManager->persist($task2);
        $this->entityManager->persist($task3);
        $this->entityManager->persist($task4);
        $this->entityManager->persist($task5);
        $this->entityManager->persist($task6);
        $this->entityManager->persist($task7);
        $this->entityManager->persist($task8);
        $this->entityManager->persist($task9);


        $document1 = new Document('Doc1');
        $document2 = new Document('Doc2');
        $document3 = new Document('Doc3');
        $document4 = new Document('Doc4');

        $documentType2_1 = new DocumentType2('Doc2_1');
        $documentType2_2 = new DocumentType2('Doc2_2');
        $documentType2_3 = new DocumentType2('Doc2_3');
        $documentType2_4 = new DocumentType2('Doc2_4');

        $location1 = new Location('loc1', 'low', LocationType::country);
        $location2 = new Location('loc2', 'low', LocationType::country);
        $location3 = new Location('loc3', 'normal', LocationType::city);
        $location4 = new Location('loc4', 'high', LocationType::region);

        $document1->addLocation($location1);
        $document1->addLocation($location2);
        $document1->addIssueCityLocation($location3);
        $document1->addIssueCityLocation($location4);

        $document2->addIssueCityLocation($location1);
        $document2->addLocation($location3);

        $documentType2_1->addLocation($location1);
        $documentType2_1->addLocation($location2);
        $documentType2_1->addIssueCityLocation($location3);
        $documentType2_1->addIssueCityLocation($location4);

        $documentType2_2->addIssueCityLocation($location1);
        $documentType2_2->addLocation($location3);

        $this->entityManager->persist($document1);
        $this->entityManager->persist($document2);
        $this->entityManager->persist($document3);
        $this->entityManager->persist($document4);
        $this->entityManager->persist($documentType2_1);
        $this->entityManager->persist($documentType2_2);
        $this->entityManager->persist($documentType2_3);
        $this->entityManager->persist($documentType2_4);
        $this->entityManager->persist($location1);
        $this->entityManager->persist($location2);
        $this->entityManager->persist($location3);
        $this->entityManager->persist($location4);

        $location1Url1 = new LocationUrl($location1, 'test1');
        $location1Url2 = new LocationUrl($location1, 'test2');
        $location1->addUrlByTitle('test3');

        $uuidTest1 = new UuidV4Entity('first');
        $uuidTest2 = new UuidV4Entity('second');
        $uuidTest3 = new UuidV4Entity('third');

        $this->entityManager->persist($uuidTest1);
        $this->entityManager->persist($uuidTest2);
        $this->entityManager->persist($uuidTest3);

        $company1 = new Company('Company 1');
        $company2 = new Company('Company 2');
        $company3 = new Company('Company 3');
        $company4 = new Company('Company 4');
        $company5 = new Company('Company 5');
        $company6 = new Company('Company 6');

        $company1->addUser($user1);
        $company1->addUser($user2);
        $company1->addUser($user3);

        $this->entityManager->persist($company1);
        $this->entityManager->persist($company2);
        $this->entityManager->persist($company3);
        $this->entityManager->persist($company4);
        $this->entityManager->persist($company5);
        $this->entityManager->persist($company6);

        $companyWithPrePersist1 = new CompanyWithPrePersist('Company 1');
        $companyWithPrePersist2 = new CompanyWithPrePersist('Company 2');
        $companyWithPrePersist3 = new CompanyWithPrePersist('Company 3');
        $companyWithPrePersist4 = new CompanyWithPrePersist('Company 4');
        $companyWithPrePersist5 = new CompanyWithPrePersist('Company 5');
        $companyWithPrePersist6 = new CompanyWithPrePersist('Company 6');

        $this->entityManager->persist($companyWithPrePersist1);
        $this->entityManager->persist($companyWithPrePersist2);
        $this->entityManager->persist($companyWithPrePersist3);
        $this->entityManager->persist($companyWithPrePersist4);
        $this->entityManager->persist($companyWithPrePersist5);
        $this->entityManager->persist($companyWithPrePersist6);

        $companyWithZeroLocation1 = new CompanyWithZeroLocation('Company 1');
        $companyWithZeroLocation2 = new CompanyWithZeroLocation('Company 2');

        $this->entityManager->persist($companyWithZeroLocation1);
        $this->entityManager->persist($companyWithZeroLocation2);

        $this->entityManager->flush();

        $companyWithZeroLocation2->setLocation($location2);
        $this->entityManager->flush();

        $task1->addViewer($user1);
        $task1->addViewer($user2);
        $task1->addViewer($user3);
        $task2->addViewer($user1);
        $task1->addViewer($user3);
        $task3->addViewer($user2);


        $bill1 = new Bill('Bill 1');
        $bill2 = new Bill('Bill 2');
        $bill3 = new Bill('Bill 3');

        $this->entityManager->persist($bill1);
        $this->entityManager->persist($bill2);
        $this->entityManager->persist($bill3);

        $account1 = new Account('account1');
        $account2 = new Account('account2');
        $account3 = new Account('account3');

        $this->entityManager->persist($account1);
        $this->entityManager->persist($account2);
        $this->entityManager->persist($account3);

        $bill1->setAccount($account3);
        $bill2->setAccount($account1);

        $userCalendarSettings1 = new UserCalendarSettings($user1);
        $userCalendarSettings3 = new UserCalendarSettings($user3);
        $userCalendarSettings3->enable();

        $this->entityManager->persist($userCalendarSettings1);
        $this->entityManager->persist($userCalendarSettings3);

        $room1 = new Room('room1', RoomType::bathroom);
        $room2 = new Room('room2', RoomType::bedroom);
        $room3 = new Room('room3', RoomType::kitchen);
        $room4 = new Room('room4', RoomType::bathroom);

        $this->entityManager->persist($room1);
        $this->entityManager->persist($room2);
        $this->entityManager->persist($room3);
        $this->entityManager->persist($room4);

        $user1->addRoom($room1);
        $user1->addRoom($room2);
        $user1->addRoom($room3);

        $room4->addToUser($user3);
        $room4->addToUser($user4);

        $this->entityManager->flush();

        $output->writeln('done');

        return Command::SUCCESS;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function clear(): void
    {
        $this->entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 0');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE users_rooms_custom');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE room');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE user_calendar_settings');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE bill');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE account');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE task_viewer');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE location');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE location_url');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE document');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE document_location');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE document2');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE document2_location');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE sprint_task');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE sprint');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE task_component');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE component');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE task_type');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE task');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE user_group');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE user_settings');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE user');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE uuid_v4_entity');

        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE company');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE company_user');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE company_pre_persist');
        $this->entityManager->getConnection()->executeQuery('TRUNCATE TABLE company_zero_location');

        $this->entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
    }
}
