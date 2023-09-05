<?php
namespace app\core;


/**
 * Summary of Response
 */
class Response
{
    /**
     * Summary of setStatusCode
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}