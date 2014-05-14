<?php

namespace Fracture\Injector;

class Inspector
{

    private $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }


    public function getBlueprint($name)
    {
        $blueprint = $this->builder->create($name);
        $this->analize($blueprint);

        return $blueprint;
    }


    private function analize($requirement)
    {
        $dependencies = $requirement->getDependencies();

        foreach ($dependencies as $dependency) {
            if ($dependency->isObject()) {
                $this->analize($dependency);
            }
        }

        return $requirement;
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
