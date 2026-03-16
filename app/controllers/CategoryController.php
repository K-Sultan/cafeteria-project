<?php
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../../utility/View.php";

class CategoryController
{
    public function index()
    {
        $categories = Product::getAllCategories();

        View::render("categories/index", [
            "categories" => $categories
        ]);
    }

    public function store()
    {
        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            $_SESSION['category_errors'] = ['Category name is required.'];
            header('Location: /categories');
            exit;
        }

        if (Product::categoryExists($name)) {
            $_SESSION['category_errors'] = ['Category already exists.'];
            header('Location: /categories');
            exit;
        }

        $id = Product::addCategory($name);

        if (!$id) {
            $_SESSION['category_errors'] = ['Failed to add category.'];
            header('Location: /categories');
            exit;
        }

        $_SESSION['category_success'] = 'Category added successfully.';
        header('Location: /categories');
        exit;
    }

    public function destroy()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['category_errors'] = ['Invalid category id.'];
            header('Location: /categories');
            exit;
        }

        $category = Product::findCategoryById($id);

        if (!$category) {
            $_SESSION['category_errors'] = ['Category not found.'];
            header('Location: /categories');
            exit;
        }

        if (Product::categoryHasProducts($id)) {
            $_SESSION['category_errors'] = ['This category cannot be deleted because it is assigned to one or more products.'];
            header('Location: /categories');
            exit;
        }

        if (!Product::deleteCategory($id)) {
            $_SESSION['category_errors'] = ['Failed to delete category.'];
            header('Location: /categories');
            exit;
        }

        $_SESSION['category_success'] = 'Category deleted successfully.';
        header('Location: /categories');
        exit;
    }
}