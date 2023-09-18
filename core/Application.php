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
    public Database $db;
    public Controller $controller;

    public Session $session;

    public static Application $app;

    /**
     * Summary of __construct
     * @param mixed $rootPath
     */
    public function __construct($rootPath, array $config)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }

    public function run()
    {
        echo $this->router->resolve();
    }

    public function getController(): \app\core\Controller
    {
        return $this->controller;
    }
    public function setController(\app\core\Controller $controller): void
    {
        $this->controller = $controller;
    }
}