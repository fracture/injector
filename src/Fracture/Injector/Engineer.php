<?php

namespace Fracture\Injector;

class Engineer
{


    public function getBlueprint($name, array $requirements = [])
    {
        $blueprint = $requirements;

        if ($this->cache && $this->cache->has($name, 'blueprint')) {
            return $this->cache->get($name, 'blueprint');
        }

        if ($this->configuration->has($name)) {
            $settings =  $this->configuration->get($name);
            $blueprint = $this->produceBlueprint($requirements, $settings);
        }

        $this->cache->set($name, 'blueprint', $blueprint);

        return $blueprint;
    }


    public function produceBlueprint($requirements, $settings)
    {
        $blueprint = $settings;

        foreach ($requirements as $key => $details) {
            $blueprint[$key] = $blueprint[$key] + $details;
        }

        return $blueprint;
    }
}
