<?php

declare(strict_types=1);

namespace MMoney\Plugins;


use MMoney\ServiceContainerInterface;

interface PluginInterface
{
    public function register(ServiceContainerInterface $serviceContainer);
}