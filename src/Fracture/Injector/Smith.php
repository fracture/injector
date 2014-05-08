<?php

namespace Fracture\Injector;

abstract class Smith implements Maker
{
    public function build($name)
    {
        if ($this->pool->has($name)) {
            return $this->pool->produce($name);
        }

        $parameters = $this->getParameters($name);
        return $this->createInstance($name, $parameters);
    }


    private function createInstance($name, $parameters)
    {
        $class = new \ReflectionClass($name);
        return $reflection->newInstanceArgs($parameters);
    }

    abstract public function getParameters($name);
}
