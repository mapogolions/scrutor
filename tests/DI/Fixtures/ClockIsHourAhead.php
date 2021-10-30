<?php

namespace Scrutor\Tests\DI\Fixtures;

class ClockIsHourAhead implements IClock
{
    private $origin;

    public function __construct(IClock $origin)
    {
        $this->origin = $origin;
    }

    public function utcNow()
    {
        return $this->origin->utcNow() + 1;
    }
}
