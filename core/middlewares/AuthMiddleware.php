<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

/**
 * Class AuthMiddleware
 * 
 * @author Lotfi Qasim
 * 
 * @package app\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];
    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }
    public function execute()
    {
        if(Application::isGuest()){
            //check if the current action which we get from the controller is in the array of restricted actions
            if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)){
                throw new ForbiddenException();
            }
        }
    }
}
