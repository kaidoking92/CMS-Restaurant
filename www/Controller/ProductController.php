<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\Product as ProductModel;
use App\Model\Checkout as CheckoutModel;
use App\Security\ProductSecurity;
use App\Security\RoleSecurity;


class ProductController {

    public function registerProduct()
    {
        $product = new ProductModel();

        $view = new View("Product/register");

        $view->assign("product", $product);

        if( !empty($_POST)){
            $result = Verificator::checkForm($product->getRegisterForm(), $_POST, $_FILES);

            if (empty($result)){
                $product->setProduct();
                $product->save();
                echo "<br>Produit enregistré";
            } else {
                var_dump($result);
            }    
        }
    }

    public function products()
    {
        
        $product = new ProductModel();
        
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

        $view = new View("Product/list",'back');
        $view->assign("products", $products);
        $view->assign("currentPage", $currentPage);
        $view->assign("pages", ceil($quantity/$interval));
        
    }

    public function removeProduct()
    {
        echo "page remove Product<br>";
        $product = new ProductModel();
        if(!empty($_POST)){
            $result = Verificator::checkForm($product->getRemoveProductForm(), $_POST);
            if(empty($result)){
                if(is_numeric($_POST['product_id'])){
                    if($_SESSION["role"]){
                        $userRole = $_SESSION["role"]; //On récupère le nom du role de l'utilisateur connecté
                        $productSecurity = new ProductSecurity();
                        $product = $productSecurity->findById($_POST['product_id']);

                        if($userRole == 'admin'){ //Si l'utilisateur connecté est un admin, alors on accepte la suppression
                            $picture = "public/img/product/" . $product->getPicture();
                            if($product->delete($_POST['product_id'])){
                                if(file_exists($picture)) {
                                    unlink($picture);
                                }
                                header('Location: /products?success');
                            }else{
                                echo "erreur lors de la suppression";
                                header('Location: /products?fail');
                            }
                        }else{
                            echo "Vous n'avez pas les droits nécessaires.";
                            header('Location: /products?fail');
                        }
                    }                   
                }

            }
        }
    }

    public function showProduct(){
        if(!empty($_GET)){
            $productSecurity = new ProductSecurity();
            $product = $productSecurity->findById($_GET['id']);

            if(!empty($_POST)){
                       
                $result = Verificator::checkForm($product->getEditProductForm(), $_POST, $_FILES);

                if(empty($result)){
                    $product->setProduct($product);                
                    $product->save();
                    echo "<br>Produit mis à jour";
                }
            }

            
        $view = new View("product/edit",'back');
        $view->assign("product", $product);

        }else{
            die('product does not exist');
        }
      
    }

    public function showPageProduct(){
        echo "page produit";
        $product = new ProductModel();
        if(!is_numeric($_GET['id'])){
            die('404 le produit n\'existe pas');
        }

        $product = $product->findById($_GET['id']);

        if($product){
            $view = new View("Product/show",'front');
            $view->assign("product", $product);
        }else{
            die('404 le produit n\'existe pas');
        }

    }

    public function AddToCart(){

        if(!isset($_SESSION['cart'])){ // On verifie qu'on a bien le panier en session, sinon on le créée
            $_SESSION['cart'] = [];
        }

        if(!empty($_POST)){
            $product = new ProductModel();  
            $result = Verificator::checkForm($product->getAddProductToCart(), $_POST, $_FILES);

            if(empty($result)){

                $product = $product->findById($_POST['product_id']);
        
                if($product){
                    $_SESSION['cart'][] = $_POST['product_id'];
                    header('Location: /pageProduct?id='.$_POST['product_id'].'&?success');
                }

            }
        }else{
            header('Location: /');
        }
      
    }

    public function EmptyCart(){
        $_SESSION['cart']= [];
    }

    public function showCart(){

        if(isset($_GET['success']) && $_GET['success'] === 'true'){
            echo "Le panié a bien été acheté";
        }
        if(!isset($_SESSION['cart'])){ // On verifie qu'on a bien le panier en session, sinon on le créée
            $_SESSION['cart'] = [];
        }
        $products = [];

        foreach( $_SESSION['cart'] as $productInCart){
            $product = new ProductModel();  
            // echo "produit :".$productInCart."<br>";

            $product = $product->findById($productInCart);
        
            if($product){
                $products[] = $product;
            }
        }
        $checkout = new CheckoutModel();

        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = intval(strip_tags(htmlspecialchars($_GET['page'])));
            
            if($currentPage == 0){
                $currentPage = 1;
            }
        }else{
            $currentPage = 1;
        }

        $quantity = count($products);
        $interval = 5;
        $checkouts = $checkout->getAllLimit(($currentPage * $interval) - $interval, $interval);  

        $view = new View("cart/show",'front');
        $view->assign("products", $products);
        $view->assign("checkout", $checkout);
        $view->assign("currentPage", $currentPage);
        $view->assign("pages", ceil($quantity/$interval));

       
      
    }

}