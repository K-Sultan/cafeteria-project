<?php


require_once __DIR__ . "/../models/User.php";


class UserController
{

    public function index()
    {
        if (!User::isAdmin()) {
            header("Location: /");
            exit;
        }
        $userModel = new User();
        $allUsers = $userModel->getAllUsers();

        $perPage = 10;
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $totalItems = count($allUsers);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $perPage;
        $users = array_slice($allUsers, $offset, $perPage);

        View::render("users", [
            "users" => $users,
            "pagination" => [
                "current_page" => $currentPage,
                "per_page" => $perPage,
                "total_items" => $totalItems,
                "total_pages" => $totalPages
            ]
        ]);
    }

    public function add()
    {
        View::render("users/add");
    }

    public function store()
    {
        $errors = [];

        // 1. Validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $room_no = trim($_POST['room_no'] ?? '');
        $extension = trim($_POST['extension'] ?? '');

        // Basic required checks
        if (empty($name)) $errors[] = "Name is required.";
        if (empty($email)) $errors[] = "Email is required.";
        if (empty($password)) $errors[] = "Password is required.";

        // Room No. & Extension Validations
        if (empty($room_no)) {
            $errors[] = "Room number is required.";
        } elseif (!is_numeric($room_no)) {
            $errors[] = "Room number must be a numeric value.";
        }

        if (empty($extension)) {
            $errors[] = "Extension is required.";
        }

        // Password & Email unique checks
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        if (User::emailExists($email)) {
            $errors[] = "This email is already registered.";
        }

        // 2. Handle Profile Picture Upload
        $profilePicName = null;
        if (empty($errors) && isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../public/uploads/";

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileExt = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                $errors[] = "Invalid image format. Allowed: " . implode(', ', $allowed);
            } else {
                $profilePicName = time() . "_" . uniqid() . "." . $fileExt;
                $targetPath = $uploadDir . $profilePicName;

                if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
                    $errors[] = "Failed to upload the profile picture.";
                }
            }
        }

        // 3. Save or Redirect
        if (empty($errors)) {
            $success = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => $password,
                'room_no'   => $room_no,
                'extension' => $extension,
                'profile_pic' => $profilePicName
            ]);

            if ($success) {
                header("Location: /users");
                exit;
            } else {
                $errors[] = "Something went wrong while saving to the database.";
            }
        }

        // If we reach here, there were errors
        $_SESSION['errors'] = $errors;
        header("Location: /users/add");
        exit;
    }

    public function home()
    {
        if (!User::isLogin()) {
            header("Location: /login");
            exit;
        }
        require_once __DIR__ . "/../models/Product.php";
        require_once __DIR__ . "/../models/Order.php";

        $products = Product::getAllAvailable();

        $users = [];
        $latestOrderItems = [];

        if (User::isAdmin()) {
            $users = User::getAllUsers();
        } else {
            $latestOrderItems = Order::getLatestOrderForUser($_SESSION['userId']);
        }



        View::render("home", [
            'products' => $products,
            'users' => $users,
            'latestOrderItems' => $latestOrderItems,
            'isAdmin' => User::isAdmin()
        ]);
    }

    public function edit($id)
    {
        $user = User::getUserById($id);
        View::render("users/edit", ["user" => $user]);
    }

    public function update($id)
    {
        $data = $_POST;
        User::update($id, $data);
        header("Location: /users");

        $data = $_POST;
        $errors = [];

        // Image Handling
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../public/uploads/";
            $fileExt = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
            $newName = time() . "_" . uniqid() . "." . $fileExt;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadDir . $newName)) {
                // Delete old pic if exists
                if (!empty($user['profile_pic'])) {
                    @unlink($uploadDir . $user['profile_pic']);
                }
                $data['profile_pic'] = $newName;
            }
        }

        User::update($id, $data);
        header("Location: /users");
        exit;
    }

    public function delete($id)
    {

        User::delete($id);
        header("Location: /users");
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: /login");
        exit;
    }
}
