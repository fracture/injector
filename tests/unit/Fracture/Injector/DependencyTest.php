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
        $this->assertEquals('Basic', $instance->getName());

        $instance = new Dependency(null);
        $this->assertFalse($instance->isObject());
        $this->assertNull($instance->getName());
    }


    public function testConcreteValidation()
    {
        $instance = new Dependency;
        $this->assertTrue($instance->isSymbolConcrete(new \ReflectionClass('Basic')));
        $this->assertFalse($instance->isSymbolConcrete(new \ReflectionClass('SomeInterface')));
    }


    public function testBasicClass()
    {
        $instance = new Dependency('Basic');
        $instance->prepare();

        $this->assertFalse($instance->hasDependencies());
    }


    public function testSimpleClass()
    {
        $instance = new Dependency('Simple');
        $instance->prepare();

        $this->assertTrue($instance->hasDependencies());
    }


    public function testDependenciesAreArray()
    {
        $instance = new Dependency('Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertInternalType('array', $dependencies);
    }


    public function testDependenciesContainDependencies()
    {
        $instance = new Dependency('BasicMultiComposite');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);
    }
}
