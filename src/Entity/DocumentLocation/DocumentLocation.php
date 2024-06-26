<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocation;

use App\Repository\DocumentLocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentLocationRepository::class)]
class DocumentLocation extends BaseDocumentLocation
{
}
