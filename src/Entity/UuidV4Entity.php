<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity, ORM\Table('uuid_v4_entity')]
class UuidV4Entity
{
    #[ORM\Id, ORM\Column(name: 'uid', type: 'uuid', unique: true)]
    private UuidV4 $uid;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    public function __construct(string $name)
    {
        $this->uid = Uuid::v4();
        $this->name = $name;
    }

    public function getUid(): UuidV4
    {
        return $this->uid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
