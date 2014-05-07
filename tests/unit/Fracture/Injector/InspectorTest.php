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
        require_once FIXTURE_PATH . '/namespaced-classes.php';
        require_once FIXTURE_PATH . '/subnamespaced-classes.php';
    }


    public function testClassWithoutConstructor()
    {
        $instance = new Inspector;
        $this->assertEquals([], $instance->getRequirements('\\Basic'));
        $this->assertEquals([], $instance->getRequirements('\\SimpleEmpty'));
    }


    public function testSimpleClass()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\Simple'));
    }


    public function testClassWithDefaultInConstructor()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
                'default' => 'foobar',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\SimpleWithDefault'));
        $this->assertEquals($expected, $instance->getRequirements('\\ExtendsSimpleWithDefault'));
    }


    public function testClassWithDefaultConstantInConstructor()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
                'default' => TEST_CONSTANT,
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\SimpleWithConstantAsDefault'));
    }


    public function testClassWithVariousParameters()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
            ],
            'beta' => [
                'type'    => 'parameter',
            ],
            'gamma' => [
                'type'    => 'parameter',
                'default' => 'foobar',
            ],
        ];


        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\SimpleWithMixedParams'));
    }



    public function testCompositeClass()
    {
        $expected = [
            'alpha' => [
                'type'    => 'class',
                'name'    => '\\Basic',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\BasicComposite'));
    }


    public function testCompositeWithInterface()
    {
        $expected = [
            'alpha' => [
                'type'    => 'interface',
                'name'    => '\\SomeInterface',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\CompsoteWithInterfaceDependency'));
    }


    public function testCompositeWithVariousDependencies()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
            ],
            'beta' => [
                'type'    => 'parameter',
                'default' => [],
            ],
            'gamma' => [
                'type'    => 'class',
                'name'    => '\\Simple',
                'default' => null,
            ],
            'delta' => [
                'type'    => 'interface',
                'name'    => '\\SomeInterface',
                'default' => null,
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\BasicCompositeWithStuff'));
    }


    public function testSimpleNamespacedClass()
    {
        $instance = new Inspector;
        $this->assertEquals([], $instance->getRequirements('\\Foobar\\First'));
    }


    public function testNamespacedClassWithDependencyFromSameNamespace()
    {
        $expected = [
            'alpha' => [
                'type'    => 'class',
                'name'    => '\\Foobar\\First',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\Foobar\\Second'));
    }


    public function testNamespacedClassWithDependencyFromGlobalNamespace()
    {
        $expected = [
            'alpha' => [
                'type'    => 'class',
                'name'    => '\\BasicComposite',
                'default' => null,
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\Foobar\\Third'));
    }


    public function testNamespacedClassWithDependencyInDifferentNamespace()
    {
        $expected = [
            'alpha' => [
                'type'    => 'class',
                'name'    => '\\Foobar\\Second',
            ],
            'beta' => [
                'type'    => 'class',
                'name'    => '\\BasicComposite',
            ],
        ];

        $instance = new Inspector;
        $this->assertEquals($expected, $instance->getRequirements('\\Lorem\\Ipsum\\Dolor'));
    }
}
