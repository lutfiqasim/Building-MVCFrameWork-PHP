<?php
namespace app\Controllers;

use app\core\Controller;
use app\models\RegisterModel;

//AuthController
/**
 * Summary of AuthController
 */
class AuthController extends Controller
{
    /**
     * Summary of login
     * @return array|string
     */
    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }
    /**
     * Summary of register
     * @param mixed $request
     * @return array|string
     */
    public function register($request)
    {
        $errors = [];
        $registerModel = new RegisterModel();
        if ($request->isPOST()) {
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                return 'Success';
            }
            // var_dump($registerModel->errors);
            return $this->render('register', [
                'model' => $registerModel
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}