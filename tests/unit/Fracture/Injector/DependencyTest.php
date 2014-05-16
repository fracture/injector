<?php


namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class DependencyTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        require_once FIXTURE_PATH . '/simple-classes.php';
    }

    public function testUninitializedDependency()
    {
        $instance = new Dependency('Basic');
        $this->assertTrue($instance->isObject());
    }
}
