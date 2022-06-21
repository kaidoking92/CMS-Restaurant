<?php

namespace App\Controller;
use App\Core\Auth;

class Admin
{
    public function __construct()
    {
        // if(!Auth::check()){
        //     header('Location: http://localhost/login');
        // }
    }

    public function dashboard()
    {
        
        echo "Ceci est un beau dashboard";
        
    }

}