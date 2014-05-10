<?php

namespace Fracture\Injector;

class Enclosure
{

    public function __constructor($instance, $cache)
    {
        $this->instance = $instance;
        $this->cache = $cache
    }


    public function __call($method, $arguments)
    {
        if (method_exists($this->target, $method)) {
            return call_user_func_array([$this->target, $method], $arguments);
        }
    }
}
