<?php

$serviceContainer = new \MMoney\ServiceContainer();

$app = new \MMoney\Application($serviceContainer);

$app->plugin(new \MMoney\Plugins\DataBasePlugin());
$app->plugin(new \MMoney\Plugins\AuthPlugin());

return $app;