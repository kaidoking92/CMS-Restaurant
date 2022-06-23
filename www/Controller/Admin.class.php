<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use App\Model\User as UserModel;
use App\Security\UserSecurity;


class Admin
{
    public function __construct()
    {
       
    }

    public function dashboard()
    {
        $userSecurity = new UserSecurity();
        $user = $userSecurity->findById($_SESSION['auth']); //On check la paire mail/password

        $view = new View("dashboard");
        $view->assign("user", $user);
    }

}