<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

#[
    ORM\Entity,
    ORM\Table('company_zero_location'),
    ORM\HasLifecycleCallbacks,
]
class CompanyWithZeroLocation
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #TODO: uncomment this code after fix
    ###[
    ###    ORM\ManyToOne(targetEntity: Location::class),
    ###    ORM\JoinColumn(name: 'location_id', nullable: true),
    ###]
    #private ?Location $location = null;

    #TODO: remove this code after fix
    #[ORM\Column(name: 'location_id', type: 'integer', nullable: false)]
    private int $locationId = 0;

    #TODO: remove this code after fix
    private ObjectManager $objectManager;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getLocation(): ?Location
    {
        #TODO: remove this code after fix
        $location = null;
        if ($this->locationId !== 0) {
            $location = $this->objectManager->getRepository(Location::class)->find($this->locationId);
            if (null === $location) {
                throw new EntityNotFoundException(
                    'Location with ID "' . $this->locationId . '" not found'
                );
            }
        }
        return $location;
    }

    public function setLocation(?Location $location): void
    {
        #TODO: remove this code after fix
        if (null !== $location) {
            if (!$location->hasId()) {
                throw new RuntimeException(
                    'Temporary restriction while location is 0 (zero) in database instead of null.'
                    . ' You mast save location before add to company. Sorry ('
                );
            }
            $this->locationId = $location->getId();
        } else {
            $this->locationId = 0;
        }
    }

    #[
        ORM\PostLoad,
        ORM\PostPersist,
    ]
    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->objectManager = $args->getObjectManager();
    }
}
