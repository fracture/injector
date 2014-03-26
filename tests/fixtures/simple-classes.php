<?php

class Simple
{

}


class Composed
{
    public function __construct(Simple $dependency)
    {

    }
}