<?php
/**
 *  USER: Lotfi Qasim 
 * 
*/
namespace app\core\middlewares;

/**
 * Class BaseMiddleware
 * @author Lotfi Qasim
 * @package app\core\middlewares
 * 
 */
abstract class BaseMiddleware
{
    abstract public function execute();
}