<?php

namespace App\Controller;
use App\Core\Auth;
use App\Core\View;
use App\Model\Checkout;


class AdminController
{
    public function __construct()
    {
       
    }

    public function dashboard()
    {
        $checkout = new Checkout();
        $checkouts = $checkout->getAllCheckout();

        $view = new View('dashboard','back'); 
        $view->assign("checkouts", $checkouts);  
    }
}