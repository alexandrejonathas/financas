<?php

    require_once __DIR__ . "/../vendor/autoload.php";

    use MMoney\Application;
    use MMoney\ServiceContainer;
    use MMoney\Plugins\RouterPlugin;

    $serviceContainer = new ServiceContainer();

    $app = new Application($serviceContainer);

    $app->plugin(new RouterPlugin());

    $app->get("index", "/", function (){
        echo "Hello world!!";
    });

    $app->start();