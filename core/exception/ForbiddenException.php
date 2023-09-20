<?php
/** User: Lotfi Qasim */

namespace app\core\exception;

/**
 * Class ForbiddenException
 * 
 * @author Lutfi 
 * @package app\core\exception
 */
class ForbiddenException extends \Exception
{
    protected $message = 'You do not have permission to access this page';
    protected $code = 403;
}