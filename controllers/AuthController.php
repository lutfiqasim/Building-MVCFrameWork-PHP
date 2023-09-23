<?php

namespace app\Controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

//AuthController
/**
 * Summary of AuthController
 */
class AuthController extends Controller
{

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
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
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if ($request->isPOST()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render(
            'login',
            [
                'model' => $loginForm
            ]
        );
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {
        
        return $this->render('profile');
    }
}
