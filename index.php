<?php
require_once  "utility/Router.php";
require_once  "utility/View.php";
require_once "app/controllers/UserController.php";
require_once "app/controllers/AuthController.php";

$router = new Router();

$router->get("/", [UserController::class, "index"]);
$router->post("/user", [UserController::class, "home"]);
$router->get("/login", [AuthController::class, "index"]);
$router->post("/login", [AuthController::class, "login"]);

$router->resolve();
