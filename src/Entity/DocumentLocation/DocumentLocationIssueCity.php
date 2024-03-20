<?php

declare(strict_types=1);

namespace App\Entity\DocumentLocation;

use App\Repository\DocumentLocationIssueCityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentLocationIssueCityRepository::class)]
class DocumentLocationIssueCity extends BaseDocumentLocation
{
}
