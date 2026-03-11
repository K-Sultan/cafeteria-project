<?php 

require_once __DIR__ . "/../models/Auth.php";


class AuthController{

  
    public function index() {
     
        
        View::render("login");
        
    }

    public function login() {
          
          $email = $_POST["email"];
          $password = $_POST["password"];
          $errorFlag=false;
          $errors = [];
          
          if(empty($email) || empty($password)){
            $errors[] = "Email and password are required.";
                        $errorFlag = true;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
                            $errorFlag = true;
            }

            if($errorFlag){
             $_SESSION["errors"] = $errors;
             header("Location: ./login");
             exit;
            }


            if($id = Auth::login($email, $password)){
                $_SESSION["userId"] = $id;

               header("Location: ./home");
                 exit;

            }else{
                 $errors[] = "Invalid Email or password.";
                 $_SESSION["errors"] = $errors;

                 header("Location: ./login");
                 exit;

            }

        

          }


    public function forgotIndex(){

    return  View::render("forgetpassword");
    }


   public function forgotpassword(){

   echo "done";
   exit;
   }


    }

