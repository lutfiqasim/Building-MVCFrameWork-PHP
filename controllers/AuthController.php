<?php
namespace app\Controllers;

use app\core\Controller;

//AuthController
class AuthController extends Controller
{
    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }
    public function register($request)
    {   
        $this->setLayout('auth');
        if ($request->isPOST()) {
            return "Handling submitted data";
        }
        return $this->render('register');
    }
}