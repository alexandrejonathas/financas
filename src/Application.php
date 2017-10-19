<?php

declare(strict_types=1);

namespace MMoney;

use MMoney\Plugins\PluginInterface;

class Application
{
    private $_serviceContainer;

    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->_serviceContainer = $serviceContainer;
    }

    public function service($name)
    {
        $this->_serviceContainer->get($name);
    }

    public function addService(string $name, $service): void
    {
        if(is_callable($service)){
            $this->_serviceContainer->addLazy($name, $service);
        }else{
            $this->_serviceContainer->add($name, $service);
        }
    }

    public function plugin(PluginInterface $plugin): void
    {
        $plugin->register($this->_serviceContainer);
    }

    public function get($name, $path, $action): Application
    {
        $routing = $this->service("routing");
        $routing->get($name, $path, $action);
        return $this;
    }

    public function start()
    {
        $route = $this->service("route");
        $callable = $route->handler;
        $callable();
    }

}