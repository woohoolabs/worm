<?php
declare(strict_types=1);

namespace WoohooLabs\Worm\Model;

abstract class AbstractModel implements ModelInterface
{
    public function __construct()
    {
        $variables = get_object_vars($this);
        foreach ($variables as $variable => $value) {
            if ($value !== null) {
                continue;
            }

            $this->$variable = $variable;
        }
    }
}
