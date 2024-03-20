<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
    ORM\Table('user_calendar_settings'),
]
class UserCalendarSettings
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private bool $enabled = true;

    #[
        ORM\OneToOne(targetEntity: User::class),
        ORM\JoinColumn(nullable: false),
    ]
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
