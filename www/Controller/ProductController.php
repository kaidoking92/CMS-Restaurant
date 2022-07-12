<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\Product as ProductModel;
use App\Security\ProductSecurity;
use App\Security\RoleSecurity;


class ProductController {

    public function registerProduct()
    {
        $product = new ProductModel();

            $view = new View("Product/register");

            $view->assign("product", $product);

            if( !empty($_POST)){

                $product->setProduct();
                $product->save();      
            }
    }

    public function products()
    {
        
        echo "Page crud Products back office";
        $product = new ProductModel();
        $products = $product->getAllProducts();

        $view = new View("Product/list",'back');
        $view->assign("products", $products);
        
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
                            $picture = "Public/img/product/" . $product->getPicture();
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
        echo "page edit produit par admin<br>";
        if(!empty($_GET)){
            $productSecurity = new ProductSecurity();
            $product = $productSecurity->findById($_GET['id']);

            if(!empty($_POST)){
                       
                $result = Verificator::checkForm($product->getEditProductForm(), $_POST, $_FILES);

                if(empty($result)){

                    // A adapter avec les catégories. 

                    if($product){  
                        $product->setName($_POST["name"]) ;
                        $product->setDescription($_POST["description"]) ;
                        $product->setPrice($_POST["price"]) ;
                        $product->setStock($_POST["stock"]);
                        $product->setIdCategory($_POST["idCategory"]);  

                        $fileName = uniqid("product_", true) . "_" . $_FILES["picture"]["name"];
                        $tmp_name = "Public/img/product/" . $fileName;
                        move_uploaded_file($_FILES["picture"]["tmp_name"], $tmp_name);
                        
                        if(file_exists("Public/img/product/" . $product->getPicture())){
                            unlink("Public/img/product/" . $product->getPicture());
                        }
                        $product->setPicture($fileName);                
                        $product->save();
                        echo "<br>Compte mis à jour";
                    }   
                }
            }

            
        $view = new View("product/edit",'back');
        $view->assign("product", $product);

        }else{
            die('product does not exist');
        }
      
    }

}