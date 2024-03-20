<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\ActiveEnumAware;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\Entity(repositoryClass: TaskRepository::class), ORM\Table('task')]
class Task implements ActiveEnumAware
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null; //### id может быть null пока entity не сохранена в БД. Можно использовать UUID чтобы избегать такой ситуации

    #[ORM\Column(type: 'string', length: 256)]
    private string $title; //### Свойство обязательное, запрашиваем его в параметрах конструктора

    #[ORM\Column(type: 'text')]
    private string $description = ''; //### Свойство обязательное, задаем default значение в описании свойства

    #[
        ORM\ManyToOne(targetEntity: TaskType::class), ### mappedBy - тут не прописываем, т.к. обратной связи с типа на таск не делаем.
        ORM\JoinColumn(nullable: false),
    ]
    private TaskType $type; //### Свойство обязательное, запрашиваем его в параметрах конструктора

    #[
        ORM\ManyToOne(targetEntity: User::class),
        ORM\JoinColumn(nullable: true),
    ]
    private ?User $assignee = null; //### Свойство не обязательное, задаем default значение = null в описании свойства

    /** @var Collection<int, Component> */
    #[ORM\ManyToMany(targetEntity: Component::class)] ### mappedBy/inversedBy - тут не прописываем, т.к. обратной связи с компонента на таск не делаем.
    private Collection $components; //### Свойство обязательное (со стороны системы), задаем default значение в конструкторе

    /** @var Collection<int, TaskViewer> */
    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskViewer::class, cascade: ['persist', 'remove'])]
    private Collection $taskViewers;

    /** @var Collection<int, Sprint> */
    #[ORM\ManyToMany(targetEntity: Sprint::class, mappedBy: 'tasks')] ### Тут добавляем mappedBy т.к. есть связь со стороны sprint
    private Collection $sprints; //### Свойство обязательное (со стороны системы), задаем default значение в конструкторе

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt; //### Свойство обязательное, устанавливаем значение в конструкторе, можно использовать Carbon при необходимости

    #[ORM\Column(type: 'enum_boolean')] ### Т.к. в БД храним boolean в виде emun (Y/N) заведен спец тип для нормальной работы в doctrine entity
    private bool $active = true; //### Свойство обязательное, устанавливаем значение в описании свойства

    public function __construct(string $title, TaskType $type)
    {
        $this->title = $title;
        $this->type = $type;
        $this->components = new ArrayCollection();
        $this->sprints = new ArrayCollection();
        $this->taskViewers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    //### Сеттер для ID не нужен, т.к. изменение id не подразумевается в базовом бизнес процессе
    public function getId(): ?int
    {
        //### Вариант с возвратом nullable ID
        return $this->id;
    }

    //### Опциональный метод
    public function getIdStrict(): int
    {
        if ($this->id === null) {
            throw new LogicException(
                'Can`t use entity of class "' . static::class . '" before persist in storage'
            );
        }
        return $this->id;
    }

    //### Опциональный метод
    public function hasId(): bool
    {
        return $this->id !== null;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    //### return из сеттера не делаем
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    //### return из сеттера не делаем
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getType(): TaskType
    {
        return $this->type;
    }

    //### return из сеттера не делаем
    public function setType(TaskType $type): void
    {
        $this->type = $type;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    //### return из сеттера не делаем
    public function setAssignee(?User $assignee): void
    {
        $this->assignee = $assignee;
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components->getValues();
    }

    //### В некоторых случая можно делать такой сеттер, но это не рекомендуется.
    //### Лучше использовать addComponent / removeComponent
    //### И тут нужно делать доп. проверки на тип того что в коллекции (иначе нарушится инкапсуляция)
    /**
     * @param Component[] $components
     */
    public function setComponents(array $components): void
    {
        $this->components->clear();
        $this->addComponent(...$components);
    }

    //### Опционально можно добавить такой метод для массового добавления компонентов
    public function addComponents(Component ...$components): void
    {
        foreach ($components as $component) {
            $this->addComponent($component);
        }
    }

    public function addComponent(Component $component): void
    {
        if (!$this->components->contains($component)) {
            $this->components->add($component);
        }
    }

    public function removeComponent(Component $component): void
    {
        $this->components->removeElement($component);
    }

    /**
     * Коллекции не возвращаем
     * @return Sprint[]
     */
    public function getSprints(): array
    {
        return $this->sprints->getValues();
    }

    public function hasSprint(Sprint $sprint): bool
    {
        return $this->sprints->contains($sprint);
    }

    //### return не делаем
    //### phpdoc можно не писать, т.к. из сигнатуры метода все понятно.
    public function addToSprint(Sprint $sprint): void
    {
        //### Тут примерный код показывающий что, можно добавить логику установки связей в модель.
        if ($sprint->isCompleted()) {
            throw new LogicException('Can`t add tasks to completed sprint');
        }
        $this->sprints->add($sprint);
        //### При необходимости по БЛ использованию сущности, можно добавлять обратную связь.
        //### Это требуется если у связываемой сущности будет использоваться связь на данную, в этом же процессе
        //### Иначе эта связь добавиться только после перезапроса сущности из БД
        if (!$sprint->hasTask($this)) {
            $sprint->addTask($this);
        }
    }

    //### return не делаем
    //### phpdoc можно не писать, т.к. из сигнатуры метода все понятно.
    public function removeFromSprint(Sprint $sprint): void
    {
        //### Тут примерный код показывающий что можно добавить логику установки связей в модель.
        if ($sprint->isCompleted()) {
            throw new LogicException('Can`t remove tasks from completed sprint');
        }
        $this->sprints->removeElement($sprint);
    }

    //### phpdoc можно не писать, т.к. из сигнатуры метода все понятно.
    //### Сеттер не нужен, т.к. изменение date created не подразумевается в базовом бизнес процессе
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    //### phpdoc можно не писать, т.к. из сигнатуры метода все понятно.
    public function isActive(): bool
    {
        return $this->active;
    }

    //### phpdoc можно не писать, т.к. из сигнатуры метода все понятно.
    //### у bool сеттеров можно задать значение по умолчанию, для более красивого вызова $obj->setActive();
    public function setActive(bool $active = true): void
    {
        $this->active = $active;
    }

    /**
     * @return TaskViewer[]
     */
    public function getTaskViewers(): array
    {
        return $this->taskViewers->getValues();
    }

    /**
     * @return User[]
     */
    public function getViewers(): array
    {
        $result = [];
        foreach ($this->taskViewers as $taskViewer) {
            $result[] = $taskViewer;
        }
        return $result;
    }

    public function hasViewer(User $viewer): bool
    {
        foreach ($this->taskViewers as $taskViewer) {
            if ($taskViewer->getViewer() === $viewer) {
                return true;
            }
        }
        return false;
    }

    public function hasNotViewer(User $viewer): bool
    {
        return !$this->hasViewer($viewer);
    }

    public function addViewer(User $viewer): void
    {
        if ($this->hasViewer($viewer)) {
            return;
        }
        $this->taskViewers->add(new TaskViewer($this, $viewer));
    }
}
