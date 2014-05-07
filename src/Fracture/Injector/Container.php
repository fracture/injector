<?php

namespace Fracture\Injector;

class Container
{

    private $smith;
    private $cache;

    public function __construct($smith, $cache = null)
    {
        $this->smith = $smith;
        $this->cache = $cache;
    }


    public function create($name)
    {
        if ($this->cache && $this->cache->has($name)) {
            return $this->cache->get($name);
        }

        $instance = $this->smith->forge($name);

        if ($this->cache && $this->cache->expecting($name)) {
            $this->cache->set($instance);
        }

        return $instance;
    }
}
