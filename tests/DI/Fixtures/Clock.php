<?php

namespace Scrutor\Tests\DI\Fixtures;

class Clock implements IClock
{
    private $value = 0;

    public function utcNow()
    {
        return $this->value++;
    }
}
