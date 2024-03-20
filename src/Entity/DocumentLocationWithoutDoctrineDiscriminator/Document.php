<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocationWithoutDoctrineDiscriminator;

use App\Entity\DocumentLocation\BaseDocumentLocation;
use App\Entity\Location;
use App\Repository\Document2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Document2Repository::class), ORM\Table('document2')]
class Document
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $title;

    /**
     * @var Collection<int, BaseDocumentLocation>
     */
    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentLocation::class, cascade: ['persist'])]
    private Collection $documentLocations;

    public function __construct(string $title)
    {
        $this->setTitle($title);
        $this->documentLocations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function addLocation(Location $location): void
    {
        //TODO: тут сделать проверку на exist
        $this->documentLocations->add(new DocumentLocation($this, $location, DocumentLocation::TYPE_LOCATION));
    }

    public function addIssueCityLocation(Location $location): void
    {
        //TODO: тут сделать проверку на exist
        $this->documentLocations->add(new DocumentLocation($this, $location, DocumentLocation::TYPE_ISSUE_CITY));
    }

    /**
     * @return Location[]
     */
    public function getAllLocations(): array
    {
        return $this->getLocationsFromDocumentLocations($this->documentLocations)->getValues();
    }

    /**
     * @return DocumentLocation[]
     */
    public function getLocations(): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('type', DocumentLocation::TYPE_LOCATION));
        return $this->getLocationsFromDocumentLocations(
            $this->documentLocations->matching($criteria)
        )->getValues();
    }

    /**
     * @return DocumentLocation[]
     */
    public function getIssueCityLocations(): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('type', DocumentLocation::TYPE_ISSUE_CITY));
        return $this->getLocationsFromDocumentLocations(
            $this->documentLocations->matching($criteria)
        )->getValues();
    }

    private function getLocationsFromDocumentLocations(Collection $documentLocations): Collection
    {
        return $documentLocations->map(
            fn(DocumentLocation $documentLocation) => $documentLocation->getLocation()
        );
    }
}
