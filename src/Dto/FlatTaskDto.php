<?php

declare(strict_types=1);

namespace App\Dto;

use Doctrine\ORM\Mapping as ORM;

class FlatTaskDto
{
    private int $id;

    private string $title;

    private ?string $assignee;

    private string $type;

    private string $component;

    public function __construct(
        int $id,
        string $title,
        ?string $assignee,
        string $type,
        string $component,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->assignee = $assignee;
        $this->type = $type;
        $this->component = $component;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAssignee(): ?string
    {
        return $this->assignee;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getComponent(): string
    {
        return $this->component;
    }
}
