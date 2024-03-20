<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\LocationType;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use LogicException;

#[ORM\Entity(repositoryClass: LocationRepository::class), ORM\Table('location')]
class Location
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $title;

    #[ORM\Column(
        type: 'string',
        nullable: true,
        columnDefinition: 'ENUM("low", "normal", "high", "new")',
    )]
    private ?string $area1 = null;

    #[ORM\Column(
        type: 'string',
        nullable: true,
        options: ['default' => 'normal'],
        columnDefinition: 'ENUM("low", "normal", "high", "new") default "normal"',
    )]
    private ?string $area2 = 'normal';

    #[ORM\Column(
        type: 'string',
        nullable: false,
        columnDefinition: 'ENUM("low", "normal", "high", "new") not null',
    )]
    private string $area3;

    #[ORM\Column(
        type: 'string',
        nullable: false,
        options: ['default' => 'normal'],
        columnDefinition: 'ENUM("low", "normal", "high", "new") default "normal" not null',
    )]
    private string $area4 = 'normal';

    #[ORM\Column(
        type: 'location_type_enum',
        nullable: true,
        enumType: LocationType::class,
    )]
    private ?LocationType $type1 = null;

    #[ORM\Column(
        type: 'location_type_enum',
        nullable: true,
        enumType: LocationType::class,
        options: ['default' => LocationType::country],
    )]
    private ?LocationType $type2 = LocationType::country;

    #[ORM\Column(
        type: 'location_type_enum',
        nullable: false,
        enumType: LocationType::class,
    )]
    private LocationType $type3;

    #[ORM\Column(
        type: 'location_type_enum',
        nullable: false,
        enumType: LocationType::class,
        options: ['default' => LocationType::city],
    )]
    private LocationType $type4 = LocationType::city;

    /** @var Collection<int, LocationUrl> */
    #[ORM\OneToMany(
        mappedBy: 'location',
        targetEntity: LocationUrl::class,
        cascade: ['persist'],
        orphanRemoval: true,
    )]
    private Collection $urls;

    public function __construct(
        string $title,
        string $area3,
        ?LocationType $type3,
    ) {
        $this->setTitle($title);
        $this->area3 = $area3;
        $this->type3 = $type3;
        $this->urls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        if (!$this->hasId()) {
            throw new LogicException(__class__ . ' has not ID');
        }
        return $this->id;
    }

    public function hasId(): bool
    {
        return null !== $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getArea1(): ?string
    {
        return $this->area1;
    }

    public function getArea2(): ?string
    {
        return $this->area2;
    }

    public function getArea3(): string
    {
        return $this->area3;
    }

    public function getArea4(): string
    {
        return $this->area4;
    }

    public function setArea1(?string $area1): void
    {
        $this->area1 = $area1;
    }

    public function setArea2(?string $area2): void
    {
        $this->area2 = $area2;
    }

    public function setArea3(string $area3): void
    {
        $this->area3 = $area3;
    }

    public function setArea4(string $area4): void
    {
        $this->area4 = $area4;
    }

    public function getType1(): ?LocationType
    {
        return $this->type1;
    }

    public function getType2(): ?LocationType
    {
        return $this->type2;
    }

    public function getType3(): LocationType
    {
        return $this->type3;
    }

    public function getType4(): LocationType
    {
        return $this->type4;
    }

    public function setType1(?LocationType $type): void
    {
        $this->type1 = $type;
    }

    public function setType2(?LocationType $type2): void
    {
        $this->type2 = $type2;
    }

    public function setType3(LocationType $type3): void
    {
        $this->type3 = $type3;
    }

    public function setType4(LocationType $type4): void
    {
        $this->type4 = $type4;
    }

    /**
     * @return LocationUrl[]
     */
    public function getUrls(): array
    {
        return $this->urls->getValues();
    }

    public function addUrl(LocationUrl $url): void
    {
        if (!$this->urls->contains($url)) {
            $this->urls->add($url);
        }
    }

    /**
     * @throws Exception
     */
    public function addUrlByTitle(string $title): void
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('title', $title));
        if ($this->urls->matching($criteria)->count()) {
            throw new \RuntimeException('Url with title "' . $title . '" already exist in location "' . $this->title . '"');
        }
        $this->addUrl(new LocationUrl($this, $title));
    }

    public function removeUrl(LocationUrl $url): void
    {
        if ($this->urls->contains($url)) {
            $this->urls->removeElement($url);
        }
    }

    public function removeUrlByTitle(string $title): void
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('title', $title));
        $urls = $this->urls->matching($criteria);
        foreach ($urls as $url) {
            $this->removeUrl($url);
        }
    }
}
