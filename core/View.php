<?php

namespace app\core;

/**
 * Class View
 * @author Lutfi 
 * @package app\core
 * 
 */
class View
{
    public string $title = '';   

    public function renderView($view, $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
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