<?php

namespace Fracture\Injector;

class Inspector
{

    private $cache;



    public function __construct(ReflectionCache $cache)
    {
        $this->cache = $cache;
    }

    public function getRequirements($class);
    {
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
    }

}