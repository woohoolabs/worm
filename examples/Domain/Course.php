<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

class Course
{
    private int $id;
    private string $name;
    private string $description;
    private int $credit;
    private string $language;
    /** @var SchoolClass[] */
    private array $classes;

    /**
     * @param SchoolClass[] $classes
     */
    public function __construct(int $id, string $name, string $description, int $credit, string $language, array $classes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->credit = $credit;
        $this->language = $language;
        $this->classes = $classes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCredit(): int
    {
        return $this->credit;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return SchoolClass[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    public function removeClass($id): void
    {
        foreach ($this->classes as $key => $class) {
            if ($class->getId() === $id) {
                unset($this->classes[$key]);

                return;
            }
        }
    }
}
