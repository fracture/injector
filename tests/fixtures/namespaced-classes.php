<?php

namespace Foobar;

class First
{

}

class Second
{
    public function __construct(First $alpha) {}
}

// from simple-classes.php

class Third
{
    public function __construct(\BasicComposite $alpha = null) {}
}