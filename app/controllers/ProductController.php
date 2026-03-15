<?php
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../../utility/View.php";

class ProductController
{
    public function index()
    {
        $products = Product::getAll();
        View::render("products/index", [
            "products" => $products
        ]);
    }
    public function home()
    {
        $products = Product::getAllAvailable();
        $categories = Product::getAllCategories();

        View::render("home", [
            "products" => $products,
            "categories" => $categories
        ]);
    }
    public function create()
    {
        $categories = Product::getAllCategories();
        View::render("products/create", [
            "categories" => $categories
        ]);
    }
    public function destroy()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /products");
            exit;
        }

        $product = Product::findById($id);

        if ($product) {
            // Remove uploaded image from disk if it exists
            if (!empty($product['image'])) {
                $imagePath = __DIR__ . "/../../public/uploads/" . $product['image'];
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }
            }
            Product::delete($id);
        }

        header("Location: /products");
        exit;
    }

    public function updateAvailability()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $isAvailable = filter_input(
            INPUT_POST,
            'is_available',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 0, 'max_range' => 1]]
        );

        if ($id === false || $id === null || $isAvailable === false || $isAvailable === null) {
            header("Location: /products");
            exit;
        }

        $product = Product::findById($id);

        if ($product) {
            Product::updateAvailability($id, (int) $isAvailable);
        }

        header("Location: /products");
        exit;
    }

    public function store()
    {
        $errors = [];

        // Basic input validation
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        if ($name === '') {
            $errors[] = "Product name is required.";
        } elseif (Product::nameExists($name)) {
            $errors[] = "This product name already exists.";
        }

        if ($price === '') {
            $errors[] = "Price is required.";
        } elseif (!is_numeric($price) || (float)$price <= 0) {
            $errors[] = "Price must be a valid positive number.";
        }

        if ($category_id === '') {
            $errors[] = "Category is required.";
        } elseif (!ctype_digit($category_id)) {
            $errors[] = "Category must be a valid numeric value.";
        }

        // Image upload validation
        $imageName = null;
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Product image is required.";
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "There was an error uploading the image.";
        }

        if (empty($errors)) {
            $uploadDir = __DIR__ . "/../../public/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($fileExt, $allowedExt, true)) {
                $errors[] = "Invalid image format. Allowed: " . implode(', ', $allowedExt);
            } else {
                $imageName = time() . "_" . uniqid() . "." . $fileExt;
                $targetFilePath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $errors[] = "Failed to upload product image.";
                }
            }
        }

        if (empty($errors)) {
            $success = Product::add($name, (float)$price, $imageName, (int)$category_id, $is_available);
            if ($success) {
                unset($_SESSION['old']);
                unset($_SESSION['errors']);
                header("Location: /products");
                exit;
            }

            $errors[] = "Something went wrong while saving the product.";
        }

        $_SESSION['old'] = [
            'name' => $name,
            'price' => $price,
            'category_id' => $category_id,
            'is_available' => $is_available
        ];
        $_SESSION['errors'] = $errors;
        header("Location: /products/create");
        exit;
    }
}