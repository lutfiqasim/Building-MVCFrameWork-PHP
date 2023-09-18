<?php
namespace app\Controllers;

use app\core\Application;
use app\core\Controller;
use app\models\User;

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
        $user = new User();
        if ($request->isPOST()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
            }
            // var_dump($registerModel->errors);
            return $this->render('register', [
                'model' => $user
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }
}