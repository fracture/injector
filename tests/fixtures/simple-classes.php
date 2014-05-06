<?php

class Basic
{

}


class SimpleEmpty
{
    public function __construct()
    {

    }
}


class SimpleWithDefault
{
    public function __construct($param = 'foobar')
    {

    }
}


class ExtendsSimpleWithDefault extends SimpleWithDefault
{

}


class PrimitiveComposite
{
    public function __construct(Basic $dependency)
    {

    }
}


class DefaultableComposite
{
    public function __construct(Basic $dependency = null)
    {

    }
}