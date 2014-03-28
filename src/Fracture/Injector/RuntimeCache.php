<?php

namespace Fracture\Injector;

class RuntimeCache implements ReflectionCache
{

    private $pool = [];

    public function hasClass($name)
    {
        return array_key_exists($name, $this->pool);
    }


    public function addClass($name, $parameters)
    {
        $this->pool[$name] = $parameters;
    }

}