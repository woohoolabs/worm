<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

use DateTimeImmutable;

class SchoolClass
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var int
     */
    private $roomId;

    /**
     * @var int
     */
    private $teacherId;

    /**
     * @var DateTimeImmutable
     */
    private $time;

    public function __construct(Course $course, int $roomId, int $teacherId, DateTimeImmutable $time)
    {
        $this->course = $course;
        $this->roomId = $roomId;
        $this->teacherId = $teacherId;
        $this->time = $time;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    public function getTeacherId(): int
    {
        return $this->teacherId;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }
}
