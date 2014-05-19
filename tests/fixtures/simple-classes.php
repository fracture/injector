<?php


define('TEST_CONSTANT', 'lorem ipsum');


class Basic{}


class SimpleEmpty
{
    public function __construct() {}
}


class Simple
{
    public function __construct($alpha) {}
}


class SimpleWithDefault
{
    public function __construct($alpha = 'foobar') {}
}


class SimpleWithConstantAsDefault
{
    public function __construct($alpha = TEST_CONSTANT) {}
}


class SimpleWithMixedParams
{
    public function __construct(array $alpha, $beta, $gamma = 'foobar') {}
}


class ExtendsSimpleWithDefault extends SimpleWithDefault {}


class BasicComposite
{
    public function __construct(Basic $alpha) {}
}


class BasicCompositeWitDefault
{
    public function __construct(Basic $alpha = null) {}
}


class BasicMultiComposite
{
    public function __construct(Basic $alpha, Basic $beta) {}
}


interface SomeInterface{}


class CompsoteWithInterfaceDependency
{
    public function __construct(SomeInterface $alpha) {}
}


class BasicCompositeWithStuff
{
    public function __construct($alpha, array $beta = [], Simple $gamma = null, SomeInterface $delta = null) {}
}

