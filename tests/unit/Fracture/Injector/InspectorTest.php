<?php

namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class InspectorTest extends PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        require_once FIXTURE_PATH . '/simple-classes.php';
    }


    public function testClassWitoutConstructor()
    {
        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals([], $instance->getRequirements('Basic'));
    }
}
