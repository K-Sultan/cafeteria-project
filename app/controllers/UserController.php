<?php 


require_once __DIR__ . "/../models/User.php";


class UserController {

    public   function index() {
     //   echo "UserController index method arsany";

         $userModel = new User();
         $users = $userModel->getAllUsers();
       //  print_r($users);
//
       //  include  "./app/views/users.php";

        View::render("home", compact("users"));
    }

    public function home() {
        echo "UserController home method";
    }
}