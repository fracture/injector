<?php

    namespace Fracture\Injector;

    include __DIR__ . '/../vendor/autoload.php';

    $foo = new Container(new Inspector(new RuntimeCache), new ProviderPool);