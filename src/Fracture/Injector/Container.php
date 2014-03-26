<?php

namespace Fracture\Injector;

class Container
{
    public function create($name)
    {
        if (!class_exists($name)) {
            throw new MissingClassException("Class '$name' was not found.");
        }

        $instance = new $name;
        return $instance;
    }
}