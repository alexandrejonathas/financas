<?php

declare(strict_types=1);

namespace MMoney\Plugins;

use Interop\Container\ContainerInterface;
use MMoney\Auth\Auth;
use MMoney\Auth\JasnyAuth;
use MMoney\ServiceContainerInterface;

class AuthPlugin implements PluginInterface
{

    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy("jasny.auth", function(ContainerInterface $container){
            return new JasnyAuth($container->get("users.repository"));
        });

        $container->addLazy("auth", function(ContainerInterface $container){
            return new Auth($container->get("jasny.auth"));
        });

    }

}