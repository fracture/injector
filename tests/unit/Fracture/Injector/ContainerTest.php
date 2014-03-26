<?php

namespace Fracture\Injector;

use Exception;
use ReflectionClass;
use PHPUnit_Framework_TestCase;


class ContainerTest extends PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        require_once FIXTURE_PATH . '/simple-classes.php';
    }

    public function testSimpleCreate()
    {
        $container = new Container;
        $this->assertInstanceOf('Simple', $container->create('Simple'));
    }
}
