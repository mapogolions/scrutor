<?php

namespace Scrutor\DI;

use Illuminate\Contracts\Container\Container;

interface ContainerContract extends Container
{
    /**
     * Decorate already registered service.
     *
     * @param  string  $abstract
     * @param  string  $decorator
     * @return void
     */
    public function decorate($abstract, $decorator);
}
