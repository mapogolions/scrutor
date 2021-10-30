<?php

namespace Scrutor\DI;

use Illuminate\Container\Container as BaseContainer;
use InvalidArgumentException;

class Container extends BaseContainer implements ContainerContract
{
    /**
     * Decorate already registered service.
     *
     * @param  string  $abstract
     * @param  string  $decorator
     * @return void
     */
    public function decorate($abstract, $decorator)
    {
        if (! isset($this->bindings[$abstract])) {
            throw new InvalidArgumentException(sprintf('%s type has not been registered', $abstract));
        }
        $binding = $this->bindings[$abstract];
        $concrete = function ($container, $parameters = []) use ($abstract, $decorator, $binding) {
            $container->addContextualBinding($decorator, $abstract, $binding['concrete']);
            return $container->resolve($decorator, $parameters);
        };
        $this->bind($abstract, $concrete, $binding['shared']);
    }
}
