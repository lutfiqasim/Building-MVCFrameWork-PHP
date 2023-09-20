<?php
namespace app\models;

use app\core\Application;
use app\core\Model;
/**
 * Class LoginForm
 * 
 */
class LoginForm extends Model
{
    public string $email='';
    public string $password='';

    public function rules():array
    {
        return [
            'email' => [self::RULE_REQUIRED,self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }
    //for labels of the form fields
    public function labels():array
    {
        return [
            'email' => 'Enter your email',
            'password' => 'Enter your password'
        ];
    }
    public function login()
    {
        $user = User::findOne(['email' => $this->email]);
        if(!$user) {
            $this->addError('email','User with this email does not exists');
            return false;
        }
        if(!password_verify($this->password,$user->password)){
            $this->addError('password', 'Password is inccorret');
            return false;
        }
        return Application::$app->login($user);
    }

}