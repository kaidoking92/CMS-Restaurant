<?php

namespace App\Middleware;

use App\Core\Middleware;

class Auth extends MiddleWare
{
    public function __construct()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    //Function to check if user is logged in by checking if session auth is set
    public function middleware()
    {
        echo "auth middleware";
        //Check if session auth is set
        if(!isset($_SESSION['auth'])){
            //Redirect to login page
            header('Location: /login');
            exit();
        }
    }

    //Function to check if has the admin role
    public function isAdmin()
    {
        //Check if session auth is set
        if(!isset($_SESSION['auth'])){
            //Redirect to login page
            header('Location: /login');
            exit();
        }
        //Check if user has admin role
        if($_SESSION['role'] != 'admin'){
            //Redirect to login page
            $error = "Forbidden";
            header('Location: /');
            exit();
        }
    }

}
