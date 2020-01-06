<?php

declare(strict_types=1);

namespace WoohooLabs\Worm\Examples\Domain;

class Name
{
    private string $firstName;
    private string $lastName;

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
