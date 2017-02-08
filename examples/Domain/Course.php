<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

class Course
{
    /**
     * @var
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $credit;

    /**
     * @var string
     */
    private $language;

    public function __construct(string $name, string $description, int $credit, string $language)
    {
        $this->name = $name;
        $this->description = $description;
        $this->credit = $credit;
        $this->language = $language;
    }

    public function getName()
    {
        return $this->name;
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
}
