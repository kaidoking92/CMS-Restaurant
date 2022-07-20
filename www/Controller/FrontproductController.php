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
        
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = intval(strip_tags(htmlspecialchars($_GET['page'])));
            
            if($currentPage == 0){
                $currentPage = 1;
            }
        }else{
            $currentPage = 1;
        }

        $quantity = intval($product->getAmountRows()['quantity']);
        $interval = 5;
        $products = $product->getAllLimit(($currentPage * $interval) - $interval, $interval);

        $view = new View('front/list');
        $view->assign("products", $products);
        $view->assign("currentPage", $currentPage);
        $view->assign("pages", ceil($quantity/$interval));
        
    }

}