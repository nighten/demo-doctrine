<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('comment')]
class Comment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 500)]
    private string $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
