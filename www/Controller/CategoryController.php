<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\Category as CategoryModel;
use App\Security\CategorySecurity;
use App\Security\RoleSecurity;


class CategoryController {

    public function registerCategory()
    {
        $category = new CategoryModel();

        $view = new View("Category/register");

        $view->assign("category", $category);

        if( !empty($_POST)){
            $result = Verificator::checkForm($category->getRegisterForm(), $_POST);

            if (empty($result)){
                $category->setCategory();
                $category->save();
                echo "<br>Catégorie enregistrée";
            } else {
                var_dump($result);
            }    
        }
    }

    public function categories()
    {
        
        echo "Page crud Categories back office";
        $category = new CategoryModel();
        $categories = $category->getAllCategories();

        $view = new View("Category/list",'back');
        $view->assign("categories", $categories);
        
    }

    public function removeCategory()
    {
        echo "page remove Category<br>";
        $category = new CategoryModel();
        if(!empty($_POST)){
            $result = Verificator::checkForm($category->getRemoveCategoryForm(), $_POST);
            if(empty($result)){
                if(is_numeric($_POST['category_id'])){
                    if($_SESSION["role"]){
                        $userRole = $_SESSION["role"]; //On récupère le nom du role de l'utilisateur connecté
                        $categorySecurity = new CategorySecurity();
                        $category = $categorySecurity->findById($_POST['category_id']);

                        if($category->getId() == 1){
                            echo "Impossible de supprimer la catégorie par défaut !";
                            header('Location: /categories?fail');
                        }else if($userRole == 'admin'){ //Si l'utilisateur connecté est un admin, alors on accepte la suppression
                            if($category->delete($_POST['category_id'])){
                                header('Location: /categories?success');
                            }else{
                                echo "erreur lors de la suppression";
                                header('Location: /categories?fail');
                            }
                        }else{
                            echo "Vous n'avez pas les droits nécessaires.";
                            header('Location: /categories?fail');
                        }
                    }                   
                }

            }
        }
    }

    public function showCategory(){
        echo "page edit catégorie par admin<br>";
        if(!empty($_GET)){
            $categorySecurity = new CategorySecurity();
            $category = $categorySecurity->findById($_GET['id']);

            if(!empty($_POST)){
                       
                $result = Verificator::checkForm($category->getEditCategoryForm(), $_POST);

                if(empty($result)){
                    $category->setCategory();                
                    $category->save();
                    echo "<br>Catégorie mise à jour";
                }
            }

            
        $view = new View("category/edit",'back');
        $view->assign("category", $category);

        }else{
            die('category does not exist');
        }
      
    }

}