<?php

namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class EngineerTest extends PHPUnit_Framework_TestCase
{

    public function testProductionOfBlueprint()
    {
        $configuration = $this->getMock('\\Fracture\\Injector\\Configuration', ['has'], [], '', false);
        $configuration->expects($this->once())
                      ->method('has')
                      ->with($this->equalTo('Foobar'))
                      ->will($this->returnValue(false));

        $instance = new Engineer($configuration);
        $this->assertEquals([], $instance->getBlueprint('Foobar', []));
    }


    public function testBlueprintWithExistingCache()
    {
        $configuration = $this->getMock('\\Fracture\\Injector\\Configuration', ['has', 'get'], [], '', false);
        $configuration->expects($this->once())
                      ->method('has')
                      ->with($this->equalTo('Foobar'))
                      ->will($this->returnValue(true));

        $configuration->expects($this->once())
                      ->method('get')
                      ->with($this->equalTo('Foobar'))
                      ->will($this->returnValue([]));


        $instance = new Engineer($configuration);

        $this->assertEquals([], $instance->getBlueprint('Foobar', []));
    }


    public function testBlueprintWithCachedOverrides()
    {

        $configuration = $this->getMock('\\Fracture\\Injector\\Configuration', ['has', 'get'], [], '', false);
        $configuration->expects($this->once())
                      ->method('has')
                      ->with($this->equalTo('Foobar'))
                      ->will($this->returnValue(true));

        $configuration->expects($this->once())
                      ->method('get')
                      ->with($this->equalTo('Foobar'))
                      ->will($this->returnValue([
                          'alpha' => [
                              'type' => 'class',
                              'name' => '\\SomeImplementation',
                          ],
                      ]));

        $settings = [
            'alpha' => [
                'type'    => 'interface',
                'name'    => '\\SomeInterface',
            ],
        ];

        $instance = new Engineer($configuration);

        $this->assertEquals([
            'alpha' => [
                'type' => 'class',
                'name' => '\\SomeImplementation',
            ],
        ], $instance->getBlueprint('Foobar', $settings));
    }
}
