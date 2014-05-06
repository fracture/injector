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


    public function testClassWithoutConstructor()
    {
        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals([], $instance->getRequirements('Basic'));
        $this->assertEquals([], $instance->getRequirements('SimpleEmpty'));
    }


    public function testSimpleClass()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('Simple'));
    }


    public function testClassWithDefaultInConstructor()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
                'default' => 'foobar',
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('SimpleWithDefault'));
        $this->assertEquals($expected, $instance->getRequirements('ExtendsSimpleWithDefault'));
    }


    public function testClassWithDefaultConstantInConstructor()
    {
        $expected = [
            'alpha' => [
                'type'    => 'parameter',
                'default' => TEST_CONSTANT,
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('SimpleWithConstantAsDefault'));
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


        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('SimpleWithMixedParams'));
    }



    public function testCompositeClass()
    {
        $expected = [
            'alpha' => [
                'type'    => 'class',
                'name'    => 'Basic',
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('BasicComposite'));
    }


    public function testCompositeWithInterface()
    {
        $expected = [
            'alpha' => [
                'type'    => 'interface',
                'name'    => 'SomeInterface',
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('CompsoteWithInterfaceDependency'));
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
                'name'    => 'Simple',
                'default' => null,
            ],
            'delta' => [
                'type'    => 'interface',
                'name'    => 'SomeInterface',
                'default' => null,
            ],
        ];

        $cache = $this->getMock('Fracture\Injector\ReflectionCache');

        $instance = new Inspector($cache);
        $this->assertEquals($expected, $instance->getRequirements('BasicCompositeWithStuff'));
    }

}
