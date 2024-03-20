<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocationWithoutDoctrineDiscriminator;

use App\Entity\Location;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('document2_location')]
class DocumentLocation
{
    public const string TYPE_LOCATION = 'location';
    public const string TYPE_ISSUE_CITY = 'issue_city';

    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'documentLocations'),
        ORM\JoinColumn(nullable: false)
    ]
    private Document $document;

    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: Location::class),
        ORM\JoinColumn(nullable: false)
    ]
    private Location $location;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    public function __construct(Document $document, Location $location, string $type)
    {
        $this->document = $document;
        $this->location = $location;
        $this->type = $type;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }
}
