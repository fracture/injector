<?php

namespace Fracture\Injector;

class DynamicSmith extends Smith
{

    private $inspector;

    public function __construct($inspector)
    {
        $this->inspector = $inspector;
    }


    public function getParameters($name)
    {
        $parameters = [];
        $requirements = $this->inspector->getRequirements($name);

        foreach ($requirements as $element) {
            var_dump($element);
        }

        return $parameters;
    }
}
