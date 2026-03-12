<?php

require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../../utility/View.php";

class ProductController
{
    public function index()
    {
        $products = Product::getAllAvailable();
        $categories = Product::getAllCategories();

        View::render("home", [
            "products" => $products,
            "categories" => $categories
        ]);
    }
}