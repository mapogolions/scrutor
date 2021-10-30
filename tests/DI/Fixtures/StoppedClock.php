<?php

namespace Scrutor\Tests\DI\Fixtures;

class StoppedClock implements IClock
{
    private $timestamp = null;

    public function __construct(IClock $origin)
    {
        $this->origin = $origin;
    }

    public function utcNow()
    {
        if (is_null($this->timestamp)) {
            $this->timestamp = $this->origin->utcNow();
        }

        return $this->timestamp;
    }
}
