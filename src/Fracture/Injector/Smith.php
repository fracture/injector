<?php

namespace Fracture\Injector;

class Smith implements Maker
{

    private $pool;
    private $inspector;
    private $configuration;

    public function __construct($pool, $inspector, $configuration)
    {
        $this->pool = $pool;
        $this->inspector = $inspector;
        $this->configuration = $configuration;
    }


    public function build($name)
    {
        if ($this->pool && $this->pool->has($name)) {
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

    public function getParameters($name)
    {
        $blueprint = $this->assembleBlueprint();
        return $this->buildParameterList($blueprint);
    }
}
