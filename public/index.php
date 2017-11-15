<?php

    use Psr\Http\Message\RequestInterface;
    use Psr\Http\Message\ServerRequestInterface;

    use Zend\Diactoros\Response;

    use MMoney\Application;
    use MMoney\ServiceContainer;
    use MMoney\Plugins\RouterPlugin;
    use MMoney\Plugins\ViewPlugin;
    use MMoney\Plugins\DataBasePlugin;
    use MMoney\Plugins\AuthPlugin;

    require_once __DIR__ . "/../vendor/autoload.php";

    require_once __DIR__ ."/../src/helper.php";

    $serviceContainer = new ServiceContainer();

    $app = new Application($serviceContainer);

    $app->plugin(new RouterPlugin());
    $app->plugin(new ViewPlugin());
    $app->plugin(new DataBasePlugin());
    $app->plugin(new AuthPlugin());

    $app->get("/", function (ServerRequestInterface $request)
    {
        $response = new \Zend\Diactoros\Response();
        $response->getBody()->write("Response com emitter do diactoros");
        return $response;
    });

    include_once __DIR__ . "/../src/Controller/category-costs.php";
    include_once __DIR__ . "/../src/Controller/users.php";
    include_once __DIR__ . "/../src/Controller/bill-receives.php";
    include_once __DIR__ . "/../src/Controller/bill-pays.php";
    include_once __DIR__ . "/../src/Controller/statements.php";
    include_once __DIR__ . "/../src/Controller/charts.php";

    include_once __DIR__ . "/../src/Controller/auth.php";

    $app->start();