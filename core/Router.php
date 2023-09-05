<?php

namespace app\core;


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
            return $this->renderView("_404");
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
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // include_once Application::$ROOT_DIR ."/views/$view.php";

    }
    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
        // include_once Application::$ROOT_DIR ."/views/$view.php";

    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout ?? 'main';
        ob_Start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();

    }

    protected function renderOnlyView($view, $params = [])
    {
        foreach ($params as $key => $value) {
            //This variable name will be evaluated to the value name
            $$key = $value;
        }
        ob_Start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();

    }

}