<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SprintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\Entity(repositoryClass: SprintRepository::class), ORM\Table('sprint')]
class Sprint
{
    public const string STATE_FUTURE = 'future';
    public const string STATE_ACTIVE = 'active';
    public const string STATE_COMPLETED = 'completed';

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 10)]
    private string $state = self::STATE_FUTURE;

    /** @var Collection<int, Task> */
    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'sprints')]
    private Collection $tasks;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->tasks = new ArrayCollection();
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

    public function isCompleted(): bool
    {
        return $this->state === self::STATE_COMPLETED;
    }

    public function isActive(): bool
    {
        return $this->state === self::STATE_ACTIVE;
    }

    public function isFuture(): bool
    {
        return $this->state === self::STATE_FUTURE;
    }

    /**
     * Коллекции не возвращаем
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks->getValues();
    }

    public function hasTask(Task $task): bool
    {
        return $this->tasks->contains($task);
    }

    public function getTasksCount(): int
    {
        return $this->tasks->count();
    }

    public function addTask(Task $task): void
    {
        if ($this->isCompleted()) {
            throw new LogicException('Can`t add tasks to completed sprint');
        }
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
        if (!$task->hasSprint($this)) {
            $task->addToSprint($this);
        }
    }

    public function removeTask(Task $task): void
    {
        if ($this->isCompleted()) {
            throw new LogicException('Can`t remove tasks from completed sprint');
        }
        $this->tasks->removeElement($task);
    }
}
