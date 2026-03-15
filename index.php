<?php
session_start();
require_once  "utility/Router.php";
require_once  "utility/View.php";
require_once "app/controllers/UserController.php";
require_once "app/controllers/AuthController.php";
require_once "app/controllers/OrderController.php"; // Added this line
require_once __DIR__ . "/app/controllers/ProductController.php";


$router = new Router();

//TODO : the / should be handled depending on the authorization(user or admin)
// $router->get("/", [UserController::class, "index"]);
// $router->post("/user", [UserController::class, "home"]);


$router->get("/login", [AuthController::class, "index"]);
$router->post("/login", [AuthController::class, "login"]);
$router->get("/forgotpassword", [AuthController::class, "forgotIndex"]);
$router->post("/forgotpassword", [AuthController::class, "forgotPassword"]);
$router->get("/users/add", [UserController::class, "add"]);
$router->post("/users", [UserController::class, "store"]);
$router->get("/users", [UserController::class, "index"]);
$router->get("/", [UserController::class, "home"]);
//$router->get('/home', [ProductController::class, 'index']);
$router->get("/", [UserController::class, "home"]);
$router->get("/logout", [UserController::class, "logout"]);
$router->post("/orders", [OrderController::class, "store"]); // Added this line
// $router->get('/home', [ProductController::class, 'index']);
$router->get("/my-orders", [OrderController::class, "index"]);
$router->get("/orders/show", [OrderController::class, "show"]);
$router->post("/orders/cancel", [OrderController::class, "cancel"]);

// $router->get('/home', [ProductController::class, 'home']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products', [ProductController::class, 'store']);
$router->post('/products/availability', [ProductController::class, 'updateAvailability']);
$router->delete('/products/delete', [ProductController::class, 'destroy']);

$router->resolve();
