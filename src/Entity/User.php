<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table('user'),
]
class User
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $active = true;

    #[
        ORM\OneToOne(mappedBy: 'user', targetEntity: UserSettings::class, cascade: ['persist']),
        ORM\JoinColumn(name: 'settings_id', referencedColumnName: 'id'),
    ]
    private UserSettings $userSettings;

    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'users_rooms_custom')]
    private Collection $rooms;

    public function __construct(string $name)
    {
        $this->setName($name);
        $this->userSettings = new UserSettings($this);
        $this->rooms = new ArrayCollection();
    }

    public function getSettings(): UserSettings
    {
        return $this->userSettings;
    }

    public function addRoom(Room $room): void
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
            $room->addToUser($this);
        }
    }

    /**
     * @return Room[]
     */
    public function getRooms(): array
    {
        return $this->rooms->getValues();
    }

    public function getId(): int
    {
        if ($this->id === null) {
            throw new RuntimeException('Entity without ID');
        }
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
