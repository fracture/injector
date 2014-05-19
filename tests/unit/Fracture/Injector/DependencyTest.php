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
        $instance = new Dependency('foobar', new \ReflectionClass('Basic'));
        $this->assertTrue($instance->isObject());
        $this->assertEquals('Basic', $instance->getType());

        $instance = new Dependency('foobar', null);
        $this->assertFalse($instance->isObject());
        $this->assertNull($instance->getType());
    }


    public function testConcreteValidation()
    {
        $instance = new Dependency('foobar', 'Basic');
        $instance->prepare();
        $this->assertTrue($instance->isConcrete());

        $instance = new Dependency('foobar', 'SomeInterface');
        $instance->prepare();
        $this->assertFalse($instance->isConcrete());
    }


    public function testBasicClass()
    {
        $instance = new Dependency('foobar', 'Basic');
        $instance->prepare();

        $this->assertFalse($instance->hasDependencies());
    }


    public function testSimpleClass()
    {
        $instance = new Dependency('foobar', 'Simple');
        $instance->prepare();

        $this->assertTrue($instance->hasDependencies());
    }


    public function testDependenciesAreArray()
    {
        $instance = new Dependency('foobar', 'Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertInternalType('array', $dependencies);
    }


    public function testDependenciesContainsConcreteDependencies()
    {
        $instance = new Dependency(null, 'BasicMultiComposite');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);

        $this->assertEquals('alpha', $dependencies[0]->getName());
        $this->assertEquals('beta', $dependencies[1]->getName());

        $this->assertEquals('Basic', $dependencies[0]->getType());
        $this->assertEquals('Basic', $dependencies[1]->getType());

    }


    public function testDependenciesContainsParametersAsDependencies()
    {
        $instance = new Dependency('foobar', 'Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);
    }
}
