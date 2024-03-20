<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('extended_company')]
class ExtendedCompany
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[
        ORM\ManyToOne(targetEntity: Company::class),
        ORM\JoinColumn(name: 'companyId', referencedColumnName: 'id', nullable: true),
    ]
    private ?Company $company;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }
}
