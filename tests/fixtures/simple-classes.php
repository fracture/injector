<?php


define('TEST_CONSTANT', 'lorem ipsum');


class Basic
{

}


class SimpleEmpty
{
    public function __construct()
    {

    }
}


class Simple
{
    public function __construct($configured)
    {

    }
}


class SimpleWithDefault
{
    public function __construct($param = 'foobar')
    {

    }
}

class SimpleWithConstantAsDefault
{
    public function __construct($param = TEST_CONSTANT)
    {

    }
}


class SimpleWithMixedParams
{
    public function __construct(array $alpha, $beta, $gamma = 'foobar')
    {

    }
}



class ExtendsSimpleWithDefault extends SimpleWithDefault
{

}



class BasicComposite
{
    public function __construct(Basic $dependency)
    {

    }
}



class BasicCompositeWithDefault
{
    public function __construct(Basic $dependency = null)
    {

    }
}