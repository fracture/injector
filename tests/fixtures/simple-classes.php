<?php

class Basic
{

}


class Simple
{
    public function __construct($param = 'default')
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