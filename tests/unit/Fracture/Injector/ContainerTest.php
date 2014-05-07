<?php

namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;

class ContainerTest extends PHPUnit_Framework_TestCase
{

    public function testWithNoCache()
    {
        $smith = $this->getMock('\\Fracture\\Injector\\Maker', ['forge'], [], '', false);
        $smith->expects($this->once())
              ->method('forge')
              ->with($this->equalTo('Foobar'))
              ->will($this->returnValue('test'));


        $instance = new Container($smith);
        $this->assertEquals('test', $instance->create('Foobar'));
    }


}