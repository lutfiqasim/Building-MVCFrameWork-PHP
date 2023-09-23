<?php

namespace app\core;

use app\core\db\Database;
use Exception;

/**
 * Summary of Application
 * @author Lutfi 
 * @package app\core;
 * 
 */
class Application
{
    /**
     * Summary of router
     * @var Router
     */
    public static string $ROOT_DIR;
    // public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public ?UserModel $user;

    public View $view;
    public Controller $controller;

    public Session $session;

    public static Application $app;

    /**
     * Summary of __construct
     * @param mixed $rootPath
     */
    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->db = new Database($config['db']);

        //for login of user 
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }
    public static function isGuest()
    {
        return !self::$app->user;
    }
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            $this->response->setStatusCode(404);
            echo $this->router->renderView(
                '_error',
                [
                    'exception' => $e
                ]
            );
        }
    }

    public function getController(): \app\core\Controller
    {
        return $this->controller;
    }
    public function setController(\app\core\Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        //Access primary value on the user
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}
