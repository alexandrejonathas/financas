<?php

declare(strict_types=1);

namespace MMoney\Plugins;


use Aura\Router\RouterContainer;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use MMoney\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

class RouterPlugin implements PluginInterface
{

    public function register(ServiceContainerInterface $container)
    {
        $routerContainer = new RouterContainer();

        /* Serve para registrar as rotas */
        $map = $routerContainer->getMap();

        /* Tem a função de identificar a rota que está sendo acessada */
        $matcher = $routerContainer->getMatcher();

        /* Gerar links com base nas rotas registradas */
        $generator = $routerContainer->getGenerator();

        $request = $this->getRequest();

        $container->add("map", $map);
        $container->add("matcher", $matcher);
        $container->add("generator", $generator);
        $container->add(RequestInterface::class, $request);

        $container->addLazy("route", function(ContainerInterface $containerInterface){
           $matcher = $containerInterface->get("matcher");
           $request = $containerInterface->get(RequestInterface::class);
           return $matcher->match($request);
        });

    }

    protected function getRequest(): RequestInterface
    {
        return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }
}