<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

class Student
{
    private int $id;
    private Name $name;
    private ?string $birthday;
    private ?string $gender;
    private string $introduction;

    public function __construct(int $id, Name $name, string $introduction, ?string $birthday = null, ?string $gender = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->introduction = $introduction;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getIntroduction(): string
    {
        return $this->introduction;
    }
}
