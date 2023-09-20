<?php

/** User: Lotfi Qasim */

namespace app\core\exception;

/**
 * Class NotFoundException
 * 
 * @author Lutfi 
 * @package app\core\exception
 */

class NotFoundException extends \Exception
{
    protected $message = 'Page Not Found';
    protected $code = 404;
}
