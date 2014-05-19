<?php

namespace Fracture\Injector;

class Dependency
{

    private $name;

    private $symbol;

    private $default;

    private $dependencies = [];

    private $hasDefault = false;

    private $needsCallable = false;


    public function __construct($name, $symbol = null)
    {
        $this->name = $name;
        $this->symbol = $symbol;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getType()
    {
        if (null === $this->symbol) {
            return null;
        }

        return $this->symbol->getName();
    }


    public function needsCallable()
    {
        return $this->needsCallable;
    }


    public function hasDefaultValue()
    {
        return $this->hasDefault;
    }


    public function getDefaultValue()
    {
        return $this->default;
    }


    public function hasDependencies()
    {
        return count($this->dependencies) > 0;
    }


    public function getDependencies()
    {
        return $this->dependencies;
    }



    public function prepare()
    {
        if (is_string($this->symbol)) {
            $this->symbol = new \ReflectionClass($this->symbol);
        }

        $this->initialize();
    }


    private function initialize()
    {
        if ($this->isObject() && $this->isConcrete()) {
            $parameters = $this->collectParameters($this->symbol);
            $this->dependencies = $this->produceDependencies($parameters);
        }
    }


    public function isObject()
    {
        return null !== $this->symbol;
    }


    public function isConcrete()
    {
        return !($this->symbol->isInterface() || $this->symbol->isAbstract() || $this->symbol->isTrait());
    }


    /**
     * @param \ReflectionClass $symbol
     * @return \ReflectionParameter[]
     */
    private function collectParameters($symbol)
    {
        $constructor = $symbol->getConstructor();

        if (null === $constructor) {
            return [];
        }

        return $constructor->getParameters();
    }


    private function produceDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->analyse($parameter);
        }

        return $dependencies;
    }


    /**
     * @param \ReflectionParameter $parameter
     */
    private function analyse(\ReflectionParameter $parameter)
    {
        $dependency = new Dependency($parameter->getName(), $parameter->getClass());
        $dependency->initialize();

        $dependency->applyContext($parameter);
        return $dependency;
    }


    /**
     * @param \ReflectionParameter $context
     */
    private function applyContext($context)
    {
        if ($context->isDefaultValueAvailable()) {
            $this->setDefaultValue($context->getDefaultValue());
            return;
        }

        if ($context->isCallable()) {
            $this->needsCallable = true;
            return;
        }
    }


    private function setDefaultValue($value)
    {
        $this->hasDefault = true;
        $this->default = $value;
    }
}
