<?php

class Basic
{

}


class SimpleWithDefault
{
    public function __construct($param = 'foobar')
    {

    }
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