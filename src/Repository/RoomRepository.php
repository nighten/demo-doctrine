<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Room;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Room>
 */
class RoomRepository extends EntityRepository
{
    public function __construct(EntityManager $manager)
    {
        parent::__construct(
            $manager,
            $manager->getClassMetadata(Room::class),
        );
    }
}
