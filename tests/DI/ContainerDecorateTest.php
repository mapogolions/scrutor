<?php

namespace Scrutor\Tests\DI;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Scrutor\DI\Container;
use Scrutor\Tests\DI\Fixtures\Clock;
use Scrutor\Tests\DI\Fixtures\StoppedClock;
use Scrutor\Tests\DI\Fixtures\ClockIsHourAhead;
use Scrutor\Tests\DI\Fixtures\ClockWrapper;
use Scrutor\Tests\DI\Fixtures\IClock;

class ContainerDecorateTest extends TestCase
{
    public function testDecorateExistingBinding()
    {
        $container = new Container();
        $container->singleton(IClock::class, Clock::class);
        $container->decorate(IClock::class, StoppedClock::class);

        $clock = $container->get(IClock::class);
        $timestamp = $clock->utcNow();

        $this->assertInstanceOf(StoppedClock::class, $clock);
        $this->assertEquals($timestamp, $clock->utcNow());
    }

    public function testChainingDecorators()
    {
        $container = Container::getInstance();
        $container->singleton(IClock::class, Clock::class);
        $container->decorate(IClock::class, StoppedClock::class);
        $container->decorate(IClock::class, ClockIsHourAhead::class);

        $clock = $container->get(IClock::class);

        $this->assertInstanceOf(ClockIsHourAhead::class, $clock);
        $this->assertEquals(1, $clock->utcNow());
    }

    public function testThrowExceptionWhenBindingDoesNotExist()
    {
        $container = new Container();
        $this->expectException(InvalidArgumentException::class);
        $container->decorate(IClock::class, Clock::class);
    }

    public function testDecoratorHasTheSameLifetimeAsDecoratedOne()
    {
        $container = new Container();
        $container->bind(IClock::class, Clock::class);
        $container->decorate(IClock::class, StoppedClock::class);

        $clock1 = $container->get(IClock::class);
        $clock2 = $container->get(IClock::class);

        $this->assertNotSame($clock1, $clock2);
        $this->assertInstanceOf(StoppedClock::class, $clock1);
        $this->assertInstanceOf(StoppedClock::class, $clock2);
    }

    public function testReboundSingleton()
    {
        $container = new Container();
        $container->singleton(IClock::class, Clock::class);

        $clock1 = $container->get(IClock::class);
        $container->decorate(IClock::class, StoppedClock::class);
        $clock2 = $container->get(IClock::class);

        $this->assertNotSame($clock1, $clock2);
        $this->assertInstanceOf(Clock::class, $clock1);
        $this->assertInstanceOf(StoppedClock::class, $clock2);
    }

    public function testReboundClockDependency()
    {
        $container = new Container();
        $container->singleton(IClock::class, Clock::class);
        $container->bind(ClockWrapper::class);

        $obj1 = $container->get(ClockWrapper::class);
        $container->decorate(IClock::class, StoppedClock::class);
        $obj2 = $container->get(ClockWrapper::class);

        $this->assertNotSame($obj1, $obj2);
        $this->assertInstanceOf(Clock::class, $obj1->getClock());
        $this->assertInstanceOf(StoppedClock::class, $obj2->getClock());
    }

    public function testMakeUnregistedInstanceRecursive()
    {
        $container = new Container();
        $container->singleton(IClock::class, Clock::class);
        $container->decorate(IClock::class, ClockIsHourAhead::class);

        $clock = $container->make(ClockIsHourAhead::class);

        $this->assertInstanceOf(ClockIsHourAhead::class, $clock);
        $this->assertEquals(2, $clock->utcNow());
        $this->assertEquals(3, $clock->utcNow());
    }

    public function testGetRegistedServiceRecursive()
    {
        $container = new Container();
        $container->singleton(IClock::class, Clock::class);
        $container->decorate(IClock::class, ClockIsHourAhead::class);
        $container->decorate(IClock::class, StoppedClock::class);
        $container->decorate(IClock::class, ClockIsHourAhead::class);

        $clock = $container->get(IClock::class);

        $this->assertInstanceOf(ClockIsHourAhead::class, $clock);
        $this->assertEquals(2, $clock->utcNow());
        $this->assertEquals(2, $clock->utcNow());
    }
}
