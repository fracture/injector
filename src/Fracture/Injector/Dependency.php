<?php

namespace Fracture\Injector;

class Dependency
{

    private $name;

    private $default;

    private $needsCallable = false;

    private $needsValue = false;

    private $dependencies = [];

    public function __construct($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }


    public function needsCallable()
    {
        return $this->needsCallable;
    }


    public function needsValue()
    {
        return $this->needsValue;
    }


    public function getDefaultValue()
    {
        return $this->default;
    }


    public function getDependencies()
    {
        return $this->dependencies;
    }

    public function initialize()
    {
        $symbol = new \ReflectionClass($this->name);
        $constructor = $symbol->getConstructor();

        if (null === $constructor) {
            return null;
        }

        $parameters = $constructor->getParameters();
        $this->dependencies = $this->collectDependencies($parameters);
    }


    private function collectDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->analyze($parameter);
        }

        return $dependencies;
    }


    private function analize($parameter)
    {
        $dependency = new Dependency($parameter->getClass());

        if ($dependency->isObject()) {
            $dependency->prepare();
        }

        $dependency->applyContext($parameter);
    }


    public function prepare()
    {
        $symbol = new \ReflectionClass($this->name);
        if ($symbol->isConcrete()) {
            $this->initialize();
        }
    }


    private function applyContext($context)
    {
        if ($context->isDefaultValueAvailable()) {
            $this->setDefaultValue($context->getDefaultValue())
            return;
        }

        if ($context->isCallable()) {
            $this->needsCallable = true;
            return;
        }

        if (!$symbol->isConcrete()) {
            $this->needsValue = true;
            return;
        }

    }


    public function isConcrete()
    {
        return $this->isObject() && !($symbol->isInterface() || $symbol->isAbstract() || $symbol->isTrait())
    }


    public function isObject()
    {
        return null !== $this->name;
    }


    private function setDefaultValue($value)
    {
        $this->needsValue = false;
        $this->default = $value;
    }
}
