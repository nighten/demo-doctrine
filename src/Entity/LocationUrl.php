<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationUrlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationUrlRepository::class), ORM\Table('location_url')]
class LocationUrl
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Location::class), ORM\JoinColumn(nullable: false)]
    private Location $location;

    #[ORM\Column(type: 'string')]
    private string $title;

    public function __construct(Location $location, string $title)
    {
        $this->location = $location;
        $this->title = $title;
        $location->addUrl($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
