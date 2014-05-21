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
        require_once FIXTURE_PATH . '/namespaced-classes.php';
        require_once FIXTURE_PATH . '/subnamespaced-classes.php';
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
        $this->assertEquals('\\Basic', $instance->getType());

        $instance = new Dependency(null, null);
        $this->assertFalse($instance->isObject());
        $this->assertNull($instance->getType());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::isConcrete
     */
    public function testConcreteValidation()
    {
        $instance = new Dependency(null, '\\Basic');
        $instance->prepare();
        $this->assertTrue($instance->isConcrete());

        $instance = new Dependency(null, 'SomeInterface');
        $instance->prepare();
        $this->assertFalse($instance->isConcrete());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::hasDependencies
     */
    public function testBasicClass()
    {
        $instance = new Dependency(null, '\\Basic');
        $instance->prepare();

        $this->assertFalse($instance->hasDependencies());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::hasDependencies
     */
    public function testSimpleClass()
    {
        $instance = new Dependency(null, '\\Simple');
        $instance->prepare();

        $this->assertTrue($instance->hasDependencies());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     */
    public function testDependenciesAreArray()
    {
        $instance = new Dependency(null, '\\Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertInternalType('array', $dependencies);
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::getName
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testDependenciesContainsConcreteDependencies()
    {
        $instance = new Dependency(null, '\\BasicMultiComposite');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);

        $this->assertEquals('alpha', $dependencies[0]->getName());
        $this->assertEquals('beta', $dependencies[1]->getName());

        $this->assertEquals('\\Basic', $dependencies[0]->getType());
        $this->assertEquals('\\Basic', $dependencies[1]->getType());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     */
    public function testDependenciesContainingParametersAsDependencies()
    {
        $instance = new Dependency(null, '\\Simple');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::isCallable
     */
    public function testDependenciesContainingCallable()
    {
        $instance = new Dependency(null, '\\SimpleWithCallable');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertContainsOnlyInstancesOf('\\Fracture\\Injector\\Dependency', $dependencies);
        $this->assertTrue($dependencies[0]->isCallable());
    }


    /**
     * @dataProvider provideDependencyWithDefaultValue
     *
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::hasDefaultValue
     * @covers Fracture\Injector\Dependency::setDefaultValue
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
            ['\\SimpleWithDefault', 'foobar'],
            ['\\SimpleWithConstantAsDefault', TEST_CONSTANT],
            ['\\BasicCompositeWitDefault', null],
        ];
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::isObject
     * @covers Fracture\Injector\Dependency::isConcrete
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     */
    public function testDependencyWithInterface()
    {
        $instance = new Dependency(null, '\\CompsoteWithInterfaceDependency');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertTrue($dependencies[0]->isObject());
        $this->assertFalse($dependencies[0]->isConcrete());
    }

    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::getName
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testNamespacedBasicClass()
    {
        $instance = new Dependency(null, '\\Foobar\\First');
        $instance->prepare();

        $this->assertEquals('\\Foobar\\First', $instance->getType());
        $this->assertNull($instance->getName());
        $this->assertFalse($instance->hasDependencies());
    }

    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testNamespacedComposeteClass()
    {
        $instance = new Dependency(null, '\\Foobar\\Second');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertEquals('\\Foobar\\First', $dependencies[0]->getType());
    }


    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testNamespacedClassWithGlobalDependency()
    {
        $instance = new Dependency(null, '\\Foobar\\Third');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertEquals('\\BasicComposite', $dependencies[0]->getType());
    }

    /**
     * @covers Fracture\Injector\Dependency::__construct
     * @covers Fracture\Injector\Dependency::prepare
     * @covers Fracture\Injector\Dependency::initialize
     * @covers Fracture\Injector\Dependency::collectParameters
     * @covers Fracture\Injector\Dependency::produceDependencies
     * @covers Fracture\Injector\Dependency::getDependencies
     * @covers Fracture\Injector\Dependency::analyse
     * @covers Fracture\Injector\Dependency::applyContext
     * @covers Fracture\Injector\Dependency::getType
     */
    public function testSubNamespacedClass()
    {
        $instance = new Dependency(null, '\\Lorem\\Ipsum\\Dolor');
        $instance->prepare();

        $dependencies = $instance->getDependencies();
        $this->assertEquals('\\Foobar\\Second', $dependencies[0]->getType());
        $this->assertEquals('\\BasicComposite', $dependencies[1]->getType());

        $secondOrder = $dependencies[0]->getDependencies();
        $this->assertEquals('\\Foobar\\First', $secondOrder[0]->getType());
    }
}
