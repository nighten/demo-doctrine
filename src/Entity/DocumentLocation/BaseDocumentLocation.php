<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocation;

use App\Entity\Location;
use App\Repository\DocumentLocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: DocumentLocationRepository::class),
    ORM\Table('document_location'),
    ORM\InheritanceType('SINGLE_TABLE'),
    ORM\DiscriminatorColumn(name: 'type', type: 'string'),
    ORM\DiscriminatorMap([
        'location' => DocumentLocation::class,
        'issue_city' => DocumentLocationIssueCity::class,
    ]),
]
abstract class BaseDocumentLocation
{
    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: Document::class),
        ORM\JoinColumn(nullable: false)
    ]
    private Document $document;

    #[
        ORM\Id,
        ORM\ManyToOne(targetEntity: Location::class),
        ORM\JoinColumn(nullable: false)
    ]
    private Location $location;

    public function __construct(Document $document, Location $location)
    {
        $this->document = $document;
        $this->location = $location;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }
}
