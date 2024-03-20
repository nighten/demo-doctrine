<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocation;

use App\Entity\Location;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class), ORM\Table('document')]
class Document
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $title;

    /** @var Collection<int, BaseDocumentLocation> */
    #[ORM\OneToMany(
        mappedBy: 'document',
        targetEntity: BaseDocumentLocation::class,
        cascade: ['persist'],
    )]
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
        $this->documentLocations->add(new DocumentLocation($this, $location));
    }

    public function addIssueCityLocation(Location $location): void
    {
        //TODO: тут сделать проверку на exist
        $this->documentLocations->add(new DocumentLocationIssueCity($this, $location));
    }

    /* >>> Example with Inheritance */
    public function getAllLocations(): array
    {
        return $this->getLocationsFromDocumentLocations($this->documentLocations)->getValues();
    }

    /**
     * @return DocumentLocation[]
     */
    public function getLocations(): array
    {
        //К сожалению Expression с использованием дескриминатора ещё не реализован в doctrine
        //https://github.com/doctrine/orm/issues/5908
        return $this->getLocationsFromDocumentLocations(
            $this->documentLocations->filter(
                fn(BaseDocumentLocation $documentLocation) => $documentLocation instanceof DocumentLocation
            )
        )->getValues();
    }

    /**
     * @return DocumentLocationIssueCity[]
     */
    public function getIssueCityLocations(): array
    {
        //К сожалению Expression с использованием дескриминатора ещё не реализован в doctrine
        //https://github.com/doctrine/orm/issues/5908
        return $this->getLocationsFromDocumentLocations(
            $this->documentLocations->filter(
                fn(BaseDocumentLocation $documentLocation) => $documentLocation instanceof DocumentLocationIssueCity
            )
        )->getValues();
    }

    private function getLocationsFromDocumentLocations(Collection $documentLocations): Collection
    {
        return $documentLocations->map(
            fn(BaseDocumentLocation $documentLocation) => $documentLocation->getLocation()
        );
    }
    /* <<< Example with Inheritance */
}
