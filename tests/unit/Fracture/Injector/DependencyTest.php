<?php


namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class DependencyTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        require_once FIXTURE_PATH . '/simple-classes.php';
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::isObject
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testUninitializedDependency()
    {
        $instance = new Dependency(null, new \ReflectionClass('Basic'));
        $this->assertTrue($instance->isObject());
        $this->assertEquals('Basic', $instance->getType());

        $instance = new Dependency(null, null);
        $this->assertFalse($instance->isObject());
        $this->assertNull($instance->getType());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::isConcrete
     */
    public function testConcreteValidation()
    {
        $instance = new Dependency(null, 'Basic');
        $instance->prepare();
        $this->assertTrue($instance->isConcrete());

        $instance = new Dependency(null, 'SomeInterface');
        $instance->prepare();
        $this->assertFalse($instance->isConcrete());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::hasDependencies
     */
    public function testBasicClass()
    {
        $instance = new Dependency(null, 'Basic');
        $instance->prepare();

        $this->assertFalse($instance->hasDependencies());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::hasDependencies
     */
    public function testSimpleClass()
    {
        $instance = new Dependency(null, 'Simple');
        $instance->prepare();

        $this->assertTrue($instance->hasDependencies());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::getDependencies
     */
    public function testDependenciesAreArray()
    {
        $instance = new Dependency(null, 'Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertInternalType('array', $dependencies);
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::getName
     * @covers Fracture\Injector\Dependency::getType
     */
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


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::getDependencies
     */
    public function testDependenciesContainingParametersAsDependencies()
    {
        $instance = new Dependency(null, 'Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);
    }


    /**
     * @dataProvider provideDependencyWithDefaultValue
     *
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::hasDefaultValue
     * @covers Fracture\Injector\Dependency::getDefaultValue
     */
    public function testDependencyWithDefaultValue($class, $value)
    {
        $instance = new Dependency(null, $class);
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertTrue($dependencies[0]->hasDefaultValue());
        $this->assertEquals($value, $dependencies[0]->getDefaultValue());
    }


    public function provideDependencyWithDefaultValue()
    {
        require_once FIXTURE_PATH . '/simple-classes.php';

        return [
            ['SimpleWithDefault', 'foobar'],
            ['SimpleWithConstantAsDefault', TEST_CONSTANT],
            ['BasicCompositeWitDefault', null],
        ];
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::isObject
     * @covers Fracture\Injector\Dependency::isConcrete
     */
    public function testDependencyWithInterface()
    {
        $instance = new Dependency(null, 'CompsoteWithInterfaceDependency');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertTrue($dependencies[0]->isObject());
        $this->assertFalse($dependencies[0]->isConcrete());
    }
}
