<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: UserSettingsRepository::class),
    ORM\Table('user_settings'),
]
class UserSettings
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[
        ORM\OneToOne(inversedBy: 'userSettings', targetEntity: User::class),
        ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id'),
    ]
    private User $user;

    #[ORM\Column(type: 'string', length: 100)]
    private string $someSetting = '';

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSomeSetting(): string
    {
        return $this->someSetting;
    }

    public function setSomeSetting(string $someSetting): void
    {
        $this->someSetting = $someSetting;
    }
}
