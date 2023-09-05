<?php
namespace app\Controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;

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
    public function contact()
    {
        return $this->render('contact');
    }
    public function handleContact(Request $request)
    {
        $body = $request->getBody();
        echo "Post data: <br>";
        print_r($body);
        exit;
        // return 'Handling submitted data';
    }

}