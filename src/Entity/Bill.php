<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BillRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: BillRepository::class),
    ORM\Table('bill'),
]
class Bill
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[
        ORM\OneToOne(inversedBy: 'bill', targetEntity: Account::class),
        ORM\JoinColumn(name: 'account_id', nullable: true),
    ]
    private ?Account $account;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
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

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }
}
