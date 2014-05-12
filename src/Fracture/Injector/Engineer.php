<?php

namespace Fracture\Injector;

class Engineer
{

    private $configuration;


    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }


    public function getBlueprint($name, array $requirements = [])
    {
        $blueprint = $requirements;

        if ($this->configuration->has($name)) {
            $settings =  $this->configuration->get($name);
            $blueprint = $this->produceBlueprint($requirements, $settings);
        }

        return $blueprint;
    }


    private function produceBlueprint($requirements, $settings)
    {
        $blueprint = $settings;

        foreach ($requirements as $key => $details) {
            $blueprint[$key] = $blueprint[$key] + $details;
        }

        return $blueprint;
    }
}
