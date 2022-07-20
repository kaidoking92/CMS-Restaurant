<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\Checkout as CheckoutModel;
use App\Model\User as UserModel;

use App\Model\Product as ProductModel;
use App\Model\Checkout_Product as CheckoutProductModel;
use App\Security\ProductSecurity;



class CheckoutController {

    public function generateCheckout()
    {
        if(!empty($_POST)){
            //Rajouter verification si le panier est vide, alors rien faire
            $checkout = new CheckoutModel();
           
            $checkout_product = new CheckoutProductModel();

            $checkout->setCheckout();
            $checkout->save();

            $existingProduct = [];
            foreach($_SESSION['cart'] as $productInCart){
                $product = new ProductModel();
                $product = $product->findById($productInCart);
                if($product){
                    $existingProduct[] = $product->getId();
                }
            }

            $checkout_product->addProductToCheckout($checkout->getLastInsertId(),$existingProduct);

            echo "id: ".$checkout->getLastInsertId();
            echo "<br>";
            echo "Panier payé !";
            //Rajouter verif si tous les produits on été bien été insert
            $_SESSION['cart'] = [];
            header('Location: /shoppingCart?success=true');

        }else{
            header('Location: /shoppingCart');
        }
    }

    public function showAllCheckout()
    {
        $checkout = new CheckoutModel();
        $product = new ProductModel();
        $user = new UserModel();

        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = intval(strip_tags(htmlspecialchars($_GET['page'])));
            
            if($currentPage == 0){
                $currentPage = 1;
            }
        }else{
            $currentPage = 1;
        }

        $quantity = intval($checkout->getAmountRows()['quantity']);
        $interval = 5;
        $checkouts = $checkout->getAllLimit(($currentPage * $interval) - $interval, $interval);

        $checkout_products = new CheckoutProductModel();

        $view = new View("Checkout/list",'back');
        $view->assign("checkouts", $checkouts);
        $view->assign("checkout_products", $checkout_products);
        $view->assign("product", $product);
        $view->assign("user", $user);
        $view->assign("currentPage", $currentPage);
        $view->assign("pages", ceil($quantity/$interval));
    }
}