<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

class Name
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getName(): string
    {
        return $this->firstName . " " . $this->lastName;
    }
}
