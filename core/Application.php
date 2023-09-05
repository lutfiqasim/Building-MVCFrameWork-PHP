<?php

namespace app\core;
/**
 * Summary of Application
 * @author Lutfi 
 * @package app\core;
 * 
 */
class Application
{
    /**
     * Summary of router
     * @var Router
     */
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;

    public static Application $app;

   
    /**
     * Summary of __construct
     * @param mixed $rootPath
     */
    public function __construct($rootPath)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
    }

    public function run()
    {
        echo $this->router->resolve();
    }

    public function getController(): \app\core\Controller
    {
        return $this->controller;
    }
    public function setController(\app\core\Controller $controller):void
    {
        $this->controller = $controller;
    }
}