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
        $parameters = [];
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (null !== $constructor) {
            $parameters = $constructor->getParameters();
        }

        return $this->collectParameterDetails($parameters);
    }


    private function collectParameterDetails($parameters)
    {
        $requirements = [];

        foreach ($parameters as $item) {
            $requirements[$item->getName()] = $this->inspectParameter($item);
        }

        return $requirements;
    }

    private function inspectParameter($parameter)
    {
        $data = ['type' => 'parameter'];

        if ($parameter->isDefaultValueAvailable()) {
            $data['default'] = $parameter->getDefaultValue();
        }

        $class = $parameter->getClass();

        if (null !== $class) {

            $data['type'] = $class->isInterface()
                                ? 'interface'
                                : 'class';

            $data['name'] = '\\' . $class->getName();
        }

        return $data;
    }
}
