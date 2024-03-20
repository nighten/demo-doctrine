<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('company')]
class Company
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    /** @var Collection<int, CompanyUser> */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: CompanyUser::class, cascade: ['persist', 'remove'])]
    private Collection $companyUsers;

    //Может можно и лучше придумать, но пока не понял как
    /** @var array<int, CompanyUser> */
    private array $removedCompanyUsers = [];

    public function __construct($name)
    {
        $this->name = $name;
        $this->companyUsers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        $result = [];
        foreach ($this->companyUsers as $companyUser) {
            $result[] = $companyUser->getUser();
        }
        return $result;
    }

    public function hasUser(User $user): bool
    {
        foreach ($this->companyUsers as $companyUser) {
            if ($companyUser->getUser() === $user) {
                return true;
            }
        }
        return false;
    }

    public function addUser(User $user): void
    {
        if ($this->hasUser($user)) {
            return;
        }
        if (array_key_exists(spl_object_id($user), $this->removedCompanyUsers)) {
            $this->companyUsers->add($this->removedCompanyUsers[spl_object_id($user)]);
        } else {
            $this->companyUsers->add(new CompanyUser($this, $user));
        }
    }

    public function removeUser(User $user): void
    {
        foreach ($this->companyUsers as $companyUser) {
            if ($companyUser->getUser() === $user) {
                $this->companyUsers->removeElement($companyUser);
                $this->removedCompanyUsers[spl_object_id($companyUser->getUser())] = $companyUser;
                return;
            }
        }
    }
}
