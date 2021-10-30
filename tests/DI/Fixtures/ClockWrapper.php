<?php

namespace Scrutor\Tests\DI\Fixtures;

class ClockWrapper
{
    private $clock;

    public function __construct(IClock $clock)
    {
        $this->clock = $clock;
    }

    public function getClock(): IClock
    {
        return $this->clock;
    }
}
