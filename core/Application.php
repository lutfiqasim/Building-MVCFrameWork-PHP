<?php

namespace app\core;

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

    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public ?DbModel $user;
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
    public static function isGuest(){
        return !self::$app->user;
    }
    public function run()
    {
        echo $this->router->resolve();
    }

    public function getController(): \app\core\Controller
    {
        return $this->controller;
    }
    public function setController(\app\core\Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user)
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
        $this->uesrr = null;
        $this->session->remove('user');
    }

    
}