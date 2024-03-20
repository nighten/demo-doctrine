<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaskViewerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskViewerRepository::class), ORM\Table('task_viewer')]
class TaskViewer
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[
        ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'taskViewers'),
        ORM\JoinColumn(nullable: false),
    ]
    private Task $task;

    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(nullable: false),
    ]
    private User $viewer;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $active = true;

    public function __construct(Task $task, User $viewer)
    {
        $this->task = $task;
        $this->viewer = $viewer;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getViewer(): User
    {
        return $this->viewer;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
