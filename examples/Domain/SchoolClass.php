<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

use DateTimeImmutable;

class SchoolClass
{
    private int $id;
    private int $roomId;
    private int $teacherId;
    /** @var Student[] */
    private array $students;
    /** @var DateTimeImmutable */
    private $datetime;

    /**
     * @param Student[] $students
     */
    public function __construct(int $id, int $roomId, int $teacherId, array $students, DateTimeImmutable $time)
    {
        $this->id = $id;
        $this->roomId = $roomId;
        $this->teacherId = $teacherId;
        $this->students = $students;
        $this->datetime = $time;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    public function getTeacherId(): int
    {
        return $this->teacherId;
    }

    /**
     * @return Student[]
     */
    public function getStudents(): array
    {
        return $this->students;
    }

    public function getDatetime(): DateTimeImmutable
    {
        return $this->datetime;
    }
}
