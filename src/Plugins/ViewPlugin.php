<?php

declare(strict_types=1);

namespace MMoney\Plugins;


use Aura\Router\RouterContainer;
use Interop\Container\ContainerInterface;
use MMoney\View\Twig\TwigGlobals;
use MMoney\View\ViewRender;
use Psr\Http\Message\RequestInterface;
use MMoney\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

class ViewPlugin implements PluginInterface
{

    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy("twig", function(ContainerInterface $container){

            $loader = new \Twig_Loader_Filesystem(__DIR__ . "/../../templates");
            $twig = new \Twig_Environment($loader);


            $auth = $container->get("auth");
            $generator = $container->get("generator");

            $twig->addExtension(new TwigGlobals($auth));

            $twig->addFunction(new \Twig_SimpleFunction("route",
                function(string $name, array $params = []) use($generator){
                    return $generator->generate($name, $params);
                })
            );

            

            return $twig;
        });

        $container->addLazy("view.renderer", function(ContainerInterface $containerInterface){
            $twigEnviroment = $containerInterface->get("twig");
            return new ViewRender($twigEnviroment);
        });
    }

}