<?php 

require_once __DIR__ . "/../models/Auth.php";


class AuthController{

  
    public function index() {
     
        
        View::render("login");


      
    }

    public function login() {
       
          header("Location: /home");
    }

}