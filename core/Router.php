<?php

namespace app\core;
use app\core\exception\NotFoundException;


/**
 * Summary of class Router
 * @author Lutfi 
 * @package app\core;
 */
class Router
{

    protected array $routes = [];
    public Request $request;
    public Response $response;

    /**
     * Summary of __construct
     * @param \app\core\Request $request
     * @param \app\core\Response $response
     */
    public function __construct(\app\core\Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();

        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView("_error",
            [
                'exception' =>throw new NotFoundException()
            ]
        );
        }
        // Check if $callback is an array with a class and method name
        // if (is_array($callback) && count($callback) === 2) {
        //     [$controller, $method] = $callback;
        //     $controllerInstance = new $controller($this->request, $this->response);
        //     return call_user_func([$controllerInstance, $method]);
        // }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            //pass apllication app controller as the instance of the call back method
            /** @var \app\core\Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }
    //can be removed and replaced with a Application::$app->view->renderView at its references
    //For clear implementation 
    public function renderView($view, $params = [])
    {
        return Application::$app->view->renderView($view,$params);
    }

}
