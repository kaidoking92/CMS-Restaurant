<?php

namespace App\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';


class Mailsender
{

    private $mail;
  

    public function __construct(){

            $this->mail = new PHPMailer(true);
            $this->mail->IsSMTP();
            $this->mail->Host = HOSTMAIL;
            $this->mail->SMTPAuth = true;
            $this->mail->Port = 587;
            $this->mail->Username = MAILUSERNAME;
            $this->mail->Password = MAILPWD;
            $this->mail->setFrom(SETMAIL);
            $this->mail->SMTPSecure = 'tls';
            
    }

    public function includeMailTemplate($template, $variables):void
    {
        if(!file_exists("View/Template/Mail/".$template.".mail.php")){
            die("Le partial ".$template." n'existe pas");
        }
        include "View/Template/Mail/".$template.".mail.php";
    }


    public function sendMail($template, $email, $name, $url = null, $data = null) 
    {

        try {
           
            $this->mail->addAddress($email);      
            $this->mail->isHTML(true);      
            
            if($template == 'register'){
                $this->mail->Subject = "Confirmation Inscription NomDuSite";
            }else{
                $this->mail->Subject = "NomDuSite";
            }

            $variables = [];
            $variables['email'] = $email;
            $variables['name'] = $name;
            $variables['url'] = $url;
            $variables['data'] = $data;

            ob_start();
            $this->includeMailTemplate($template, $variables);
            $template = ob_get_clean();

            $this->mail->Body = $template;
            $this->mail->send();

            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {mail->ErrorInfo}";
        }


    }


   


}