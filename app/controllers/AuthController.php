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

               header("Location: ./");
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

    $email = $_POST["email"] ?? "";
    $errors = [];
    if(empty($email)){
      $errors[] = "Email  required.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if(!empty($errors)){
        $_SESSION["errors"] = $errors;
        header("Location: ./forgotpassword");
        exit;
    }

    if(!Auth::emailExists($email)){
        $errors[] = "Email does not exist.";
        $_SESSION["errors"] = $errors;
    }else{

        $newPassword = "12345678";
        if(Auth::updatePassword($email, $newPassword)){
            $_SESSION["success"] = "A reset link has been sent to your email.";
        }else{
            $errors[] = "Please try again.";
            $_SESSION["errors"] = $errors;
            
        }

    }
    
    header("Location: ./forgotpassword");
    exit;
   }


    }

