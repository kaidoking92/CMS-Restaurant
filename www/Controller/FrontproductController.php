<?php

namespace App\Controller;
use App\Core\Auth;
use App\Core\View;
use App\Model\Product;


class FrontproductController
{
    public function __construct()
    {
       
    }

    public function showAllProduct()
    {
        
        $product = new Product();
        $products = $product->getAll();

        $view = new View('front/list');
        $view->assign("products", $products);

        
    }

}