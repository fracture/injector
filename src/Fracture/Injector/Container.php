<?php

namespace Fracture\Injector;

class Container
{

    private $inspector;
    private $engineer;
    private $cache;

    public function __construct($inspector, $engineer, $smith)
    {
        $this->inspector = $inspector;
        $this->engineer = $engineer;
        $this->smith = $smith;
    }


    public function create($name)
    {


        $blueprint = $this->inspector->getBlueprint($name);




        $blueprint = $this->engineer->getBlueprint($name, $requirements);
        $instance = $this->smith->forge($name, $blueprint);

        return $instance;
    }
}
