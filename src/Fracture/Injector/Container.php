<?php

namespace Fracture\Injector;

class Container
{

    private $instance;
    private $pool;

    public function __construct($inspector, $pool)
    {
        $this->inspector = $instance;
        $this->pool = $pool;
    }


    public function create($class)
    {
        return $this->buildInstance($class, []);
    }

    private function buildInstance($class, array $stack)
    {
        if (in_array($class, $stack)) {
            throw new CyclicDependencyException;
        }

        $stack[] = $class;

        $requirements = $this->inspector->getRequirements($class);
        $dependencies = $this->produceDependencies($requirements, $stack);

        return (new ReflectionClass($class))->newInstanceArgs($dependencies);
    }


    private function produceDependencies($requirements, array $stack)
    {
        $dependencies = [];
        foreach ($requirements as $type => $parameters) {
            $dependencies[] = $this->buildInstance($name, $stack);
        }
        return $dependencies;
    }
}