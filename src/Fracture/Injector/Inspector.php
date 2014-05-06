<?php

namespace Fracture\Injector;

class Inspector
{

    private $cache;



    public function __construct(ReflectionCache $cache)
    {
        $this->cache = $cache;
    }

    public function getRequirements($class)
    {
        $requirements = [];

        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (null !== $constructor) {
            $parameters = $constructor->getParameters();
            foreach ($parameters as $item) {
                var_dump($item->getClass());
            }
        }
        return $requirements;
    }
}
