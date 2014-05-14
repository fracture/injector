<?php

namespace Fracture\Injector;

class Dependency
{

    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }


    public function getDependencies()
    {
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return null;
        }

        $parameters = $constructor->getParameters();
        return $this->collectDependencies($parameters);
    }


    private function collectDependencies($parameters)
    {
    }


    public function isObject()
    {

    }
}
