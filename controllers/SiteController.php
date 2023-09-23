<?php
namespace app\Controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ContactForm;

/**
 * Class SiteController
 * 
 * @author Lutfi 
 * 
 * @package app\Controllers
 * 
 */
class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "Lotfi Qasim"
        ];
        return $this->render('home', $params);
    }
    public function contact(Request $request,Response $response)
    {
        $contact = new ContactForm();
        if($request->isPOST()){
            $contact->loadData($request->getBody());
            if($contact->validate() && $contact->send()){
                Application::$app->session->setFlash('success','Thanks for contacting us.');
                return $response->redirect('/contact');
            }
        }
        return $this->render('contact',[
            'model' => $contact
        ]);
    }
    // public function handleContact(Request $request)
    // {
    //     $body = $request->getBody();
    //     echo "Post data: <br>";
    //     print_r($body);
    //     exit;
    //     // return 'Handling submitted data';
    // }

}