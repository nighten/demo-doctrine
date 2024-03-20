<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('company_user')]
class CompanyUser
{
    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'companyUsers'),
        ORM\JoinColumn(nullable: false),
    ]
    private Company $company;

    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(nullable: false),
    ]
    private User $user;

    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
