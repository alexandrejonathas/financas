<?php

declare(strict_types=1);

namespace MMoney;

use MMoney\Plugins\PluginInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\SapiEmitter;

class Application
{
    private $_serviceContainer;

    private $befores = [];

    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->_serviceContainer = $serviceContainer;
    }

    public function service($name)
    {
        return $this->_serviceContainer->get($name);
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

    public function get($path, $action, $name=null): Application
    {
        $map = $this->service("map");
        $map->get($name, $path, $action);
        return $this;
    }

    public function post($path, $action, $name=null): Application
    {
        $map = $this->service("map");
        $map->post($name, $path, $action);
        return $this;
    }

    public function redirect(string $path):ResponseInterface
    {
        return new \Zend\Diactoros\Response\RedirectResponse($path);
    }

    public function route(string $path, array $params = []): ResponseInterface
    {
        $generator = $this->service("generator");
        $path = $generator->generate($path, $params);
        return $this->redirect($path);
    }

    public function before(callable $callback): Application
    {
        array_push($this->befores, $callback);
        return $this;
    }

    protected function runBefores():?ResponseInterface
    {
        foreach ($this->befores as $callback){
            $result = $callback($this->service(RequestInterface::class));
            if($result instanceof ResponseInterface){
                return $result;
            }
        }
        return null;
    }

    public function start(): void
    {
        $route = $this->service("route");

        /** @var ServerRequestInterface $request */
        $request = $this->service(RequestInterface::class);

        if(!$route){
            echo "Page not found";
            exit;
        }

        foreach ($route->attributes as $key => $value){
            $request = $request->withAttribute($key,$value);
        }

        $result = $this->runBefores();
        if($result){
            $this->emitResponse($result);
            return;
        }

        $callable = $route->handler;

        $response = $callable($request);
        $this->emitResponse($response);
    }

    public function emitResponse(ResponseInterface $response): void
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

}