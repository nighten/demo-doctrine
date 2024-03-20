<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('extended_company_comment')]
class ExtendedCompanyComment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[
        ORM\ManyToOne(targetEntity: Comment::class),
        ORM\JoinColumn(name: 'commentId', referencedColumnName: 'id'),
    ]
    private Comment $comment;

    #[
        ORM\ManyToOne(targetEntity: ExtendedCompany::class),
        ORM\JoinColumn(name: 'entityId', referencedColumnName: 'id'),
    ]
    private ExtendedCompany $extendedCompany;

    public function __construct(Comment $comment, ExtendedCompany $extendedCompany)
    {
        $this->comment = $comment;
        $this->extendedCompany = $extendedCompany;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function getExtendedCompany(): ExtendedCompany
    {
        return $this->extendedCompany;
    }
}
