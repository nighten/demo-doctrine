<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\RoomType;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class), ORM\Table('room')]
class Room
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(
        type: 'string',
        length: 100,
        nullable: true,
        enumType: RoomType::class,
    )]
    private ?RoomType $type1 = null;

    #[ORM\Column(
        type: 'string',
        length: 100,
        nullable: true,
        enumType: RoomType::class,
        options: ['default' => RoomType::bathroom],
    )]
    private ?RoomType $type2 = RoomType::bathroom;

    #[ORM\Column(
        type: 'string',
        length: 100,
        nullable: false,
        enumType: RoomType::class,
    )]
    private RoomType $type3;

    #[ORM\Column(
        type: 'string',
        length: 100,
        nullable: false,
        enumType: RoomType::class,
        options: ['default' => RoomType::bathroom],
    )]
    private RoomType $type4 = RoomType::bathroom;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'rooms')]
    private Collection $users;

    public function __construct(
        string $name,
        RoomType $type3,
    ) {
        $this->name = $name;
        $this->type3 = $type3;
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        //### Вариант с возвратом not nullable ID и исключением
        if ($this->id === null) {
            throw new \LogicException(
                'Can`t use entity of class "' . static::class . '" before persist in storage'
            );
        }
        return $this->id;
    }

    //### Опциональный метод
    public function hasId(): bool
    {
        return $this->id !== null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType1(): ?RoomType
    {
        return $this->type1;
    }

    public function getType2(): ?RoomType
    {
        return $this->type2;
    }

    public function getType3(): RoomType
    {
        return $this->type3;
    }

    public function getType4(): RoomType
    {
        return $this->type4;
    }

    public function setType1(?RoomType $type1): void
    {
        $this->type1 = $type1;
    }

    public function setType2(?RoomType $type2): void
    {
        $this->type2 = $type2;
    }

    public function setType3(RoomType $type3): void
    {
        $this->type3 = $type3;
    }

    public function setType4(RoomType $type4): void
    {
        $this->type4 = $type4;
    }

    public function addToUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRoom($this);
        }
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
}
