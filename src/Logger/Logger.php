<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Logger;

class Logger
{
    /**
     * @var array
     */
    private $log;

    /**
     * @var bool
     */
    private $isEnabled;

    public function __construct(bool $isEnabled)
    {
        $this->log = [];
        $this->isEnabled = $isEnabled;
    }

    public function log(string $query, $result)
    {
        if ($this->isEnabled === false) {
            return;
        }

        $this->log[] = "[" . date("Y-m-d H:i:s") . "] Result: $result, query: $query";
    }

    public function getLog(): array
    {
        return $this->log;
    }
}
