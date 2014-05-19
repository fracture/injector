<?php

namespace Fracture\Injector;

class Dependency
{

    private $name;

    private $type;

    private $default;

    private $needsCallable = false;

    private $needsValue = false;

    private $dependencies = [];

    public function __construct($name, $type = null)
    {
        $this->name = $name;
        $this->type = $type;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getType()
    {
        return $this->type;
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
        $symbol = new \ReflectionClass($this->type);
        if ($this->isSymbolConcrete($symbol)) {
            $this->dependencies = $this->initialize($symbol);
        }
    }


    public function isSymbolConcrete($symbol)
    {
        return !($symbol->isInterface() || $symbol->isAbstract() || $symbol->isTrait());
    }


    /**
     * @param \ReflectionClass $symbol
     * @return \ReflectionParameter[]
     */
    private function initialize($symbol)
    {
        $constructor = $symbol->getConstructor();

        if (null === $constructor) {
            return [];
        }

        $parameters = $constructor->getParameters();
        return $this->collectDependencies($parameters);
    }


    private function collectDependencies($parameters)
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
        $dependency = new Dependency($parameter->getClass());

        if ($dependency->isObject()) {
            $dependency->prepare();
        }

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


    public function isObject()
    {
        return null !== $this->type;
    }


    private function setDefaultValue($value)
    {
        $this->needsValue = false;
        $this->default = $value;
    }
}
