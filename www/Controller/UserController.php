<?php
namespace App\Controller;

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Mailsender;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Security\UserSecurity;
use App\Security\RoleSecurity;


class UserController {

    public function __construct()
    {
        // Auth::check();
    }

    public function login()
    {
        $user = new UserModel();
        $view = new View("Login");

        $view->assign("user", $user);
        
        if(!empty($_POST)){
            $result = Verificator::checkForm($user->getLoginForm(), $_POST);

            if(empty($result)){ // Si pas d'erreur
                $emailToCheck = $_POST['email'];
                $passwordToCheck = $_POST['password'];

                $userSecurity = new UserSecurity();
                $roleSecurity = new RoleSecurity();

                $user = $userSecurity->checkLogin($emailToCheck,$passwordToCheck); //On check la paire mail/password
                if($user){ // Si un utilisateur est renvoyé, on crée la session
                    echo "sucess";
                    $user->generateAuthToken();
                    $user->save();

                   
                    $_SESSION['auth'] = $user->getId();
                    $_SESSION['auth_token'] = $user->getAuthToken();
                    $_SESSION['email'] = $user->getEmail();
                    $_SESSION['firstname'] = $user->getFirstname();
                    $_SESSION['role'] = $roleSecurity->getRoleNameById($user->getRoleId());

                    // $_SESSION['role'] = Role::getRoleById($user->getRoleId());
                    // faire une methode UserSecurity->getRoleById?

                    $view->assign("firstname",  $_SESSION['firstname']);
                    // $view->assign("lastname", "Skrzypczyk");
                    header('Location: /');
                }else{
                    echo "Identifiant ou mot de passe incorrect";
                }
                
            }else{
                echo implode("<br>",$result); //renvoi les erreurs du formulaires
            }
           
        }     

    }


    public function register()
    {
        $user = new UserModel();

            $view = new View("register");

            $view->assign("user", $user);

            if( !empty($_POST)){

                $result = Verificator::checkForm($user->getRegisterForm(), $_POST);
                if (empty($result)) {
                $user->setUser();
                $user->save();

                $mailtest = new Mailsender();
                $mailtest->sendMail('register', $user->getEmail(),$user->getFirstname(),"http://localhost/activation?code=".$user->getToken());
                }
                else {
                    echo "Erreur";
                }
            
            }
        

    }

    public function activation()
    {
        if(isset($_GET['code']) && !empty($_GET['code'])){
            $userSecurity = new UserSecurity();
            echo $userSecurity->validateAccount($_GET['code']); //On check si un compte possède le token
        }else{
            header('Location: /');
        }
    }


    public function logout()
    {
        $view = new View("logout");
        session_destroy();
    }


    public function pwdforget()
    {
        $user = new UserModel();
        $view = new View("forget");

        $view->assign("user", $user);

        if( !empty($_POST)){

            $result = Verificator::checkForm($user->getForgetPasswordForm(), $_POST);
            if (empty($result)) {
                $emailToCheck = $_POST['email'];

                $userSecurity = new UserSecurity();
                $user = $userSecurity->findByUsermail($emailToCheck); //On check si un compte possède le mail

                if($user){
                    $user->generateResetToken();
                    $user->save();

                    $mailtest = new Mailsender();
                    $mailtest->sendMail('forget', $user->getEmail(),$user->getFirstname(),"http://localhost/reset-pwd?code=".$user->getResetToken());
                    echo "Mail de récupération envoyé";
                }else{
                    echo "Compte inexistant";
                } 

            // $mailtest = new Mailsender();
            // $mailtest->sendMail('register', $user->getEmail(),$user->getFirstname(),"http://localhost/activation?code=".$user->getToken());
            }
            else {
                echo "Erreur";
            }
        
        }


    }

    public function resetpassword()
    {
        if(isset($_GET['code']) && !empty($_GET['code'])){
            $userSecurity = new UserSecurity();
            $user =  $userSecurity->findByResetToken($_GET['code']); //On check si un compte possède le token
        
            if($user){ //Si c'est le cas on affiche le formulaire de reset password, sinon on redirige
                $view = new View("reset");
                $view->assign("user", $user);

                if($_POST){
                    $result = Verificator::checkForm($user->getResetPasswordForm(), $_POST);

                    if(empty($result)){
                        $user->setPassword($_POST["password"]) ;
                        $user->emptyResetToken();
                        $user->save();
                        echo "Le mot de passe à bien été changé - Redirection";
                    }
                }

            }else{

            header('Location: /');
        }
        
        }else{
            header('Location: /');
        }
    }

    public function showprofile()
    {
        $userSecurity = new UserSecurity();
        $user = $userSecurity->findByUsermail($_SESSION['email']); //On check si un compte possède le mail
        if($user){
            if($_POST){
                // print_r($_POST);
                $resultProfil = Verificator::checkForm($user->getProfileForm(), $_POST);
            }
            $view = new View("profil");
            $view->assign("user", $user);
        }else{
            header('Location: /');
        }
    }

    public function editpasswordprofile()
    {
        $userSecurity = new UserSecurity();
        $user = $userSecurity->findByUsermail($_SESSION['email']); //On check si un compte possède le mail
        if($user){
            $view = new View("editpassword");
            $view->assign("user", $user);

            if($_POST){
                $result = Verificator::checkForm($user->getChangePasswordForm(), $_POST);
    
                if(empty($result)){
                    if(password_verify($_POST["currentPassword"],$user->getPassword())){
                        $user->setPassword($_POST["password"]) ;
                        $user->save();
                        $message = "Le mot de passe à bien été changé - Redirection";
                    }else{
                        $message = "mauvais mot de passe";
                    }
                   
                    // header('Location: /');
                }else{
                    // header('Location: /');
                    $message = 'Erreur lors de lenvoi du formulaire';
                }
                $view->assign("message", $message);
            }

        }else{
            header('Location: /');
        }
       
        
    }

    public function sendmail()
    {
        echo "page d'envoi mail<br>";
        
        $mailtest = new Mailsender();
        $mailtest->sendMail('register',"yohan@mailexemple.com","yohan","https://google.com");
        // sendMail($template, $email, $name, $url = null, $data = null) 

    }

    public function users()
    {
        
        $userSecurity = new UserSecurity();
        
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = intval(strip_tags(htmlspecialchars($_GET['page'])));
            
            if($currentPage == 0){
                $currentPage = 1;
            }
        }else{
            $currentPage = 1;
        }

        $quantity = intval($userSecurity->getAmountRows()['quantity']);
        $interval = 5;
        $users = $userSecurity->getAllLimit(($currentPage * $interval) - $interval, $interval);


        $view = new View("User/list",'back');
        $view->assign("users", $users);
        $view->assign("currentPage", $currentPage);
        $view->assign("pages", ceil($quantity/$interval));
        // foreach($users as $user){
        //     echo $user->getFirstname();
        //     echo "<br>";
        // }
        
    }

    public function removeUser()
    {
        echo "page remove user<br>";
        $user = new UserModel();
        if(!empty($_POST)){
            $result = Verificator::checkForm($user->getRemoveUserForm(), $_POST);
            if(empty($result)){
                echo "formulaire validé <br>";

                if(is_numeric($_POST['user_id'])){
                    $userSecurity = new UserSecurity();
                    $roleSecurity = new RoleSecurity();
                    echo "User to be deleted :".$_POST['user_id'];

                    $user = $userSecurity->findById($_POST['user_id']); //On récupère l'utilisateur par son ID
                    if($user){
                        $userRole = $roleSecurity->getRoleNameById($user->getRoleId()); //On récupère le nom du role de l'utilisateur

                        if($userRole != 'admin' && $user->getRoleId() != 3){ //Si l'utilisateur n'est pas un admin, alors on accepte la suppression
                            echo "sera supprimé car utilisateur";
                             if($user->delete($_POST['user_id'])){
                                header('Location: /users?sucess');
                            }else{
                                echo "erreur lors de la suppression";
                                header('Location: /users?fail');
                            }
                        }else{
                            header('Location: /users?fail');
                        }
                    }                   
                }

            }
        }
    }

    public function showUser(){
        if(!empty($_GET)){
            $userSecurity = new UserSecurity();
            $user = $userSecurity->findById($_GET['id']);

            if(!empty($_POST)){
                $result = Verificator::checkForm($user->getEditUserForm(), $_POST);
                if(empty($result)){

                    if($user){
                        $roleSecurity = new RoleSecurity();
                        $userRole = $roleSecurity->getRoleNameById($user->getRoleId()); //On récupère le nom du role de l'utilisateur

                        if($userRole != 'admin' && $user->getRoleId() != 3){ //Si l'utilisateur n'est pas un admin, alors on accepte la modification
                                                      
                                $user->setfirstName($_POST['firstname']);
                                $user->setlastname($_POST['lastname']);
                                $user->setRoleId($_POST['role']);
                                $user->save();
                                echo "<br>Compte mis à jour";
                           
                        }else{
                            echo "<br>Compte administrateur non modifiable";
                        }
                    }   
                }
            }

            
        $view = new View("User/edit",'back');
        $view->assign("user", $user);

        }else{
            die('user does not exist');
        }
      
    }

}