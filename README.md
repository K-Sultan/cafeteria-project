# Cafeteria Management System

A full-stack PHP web application for managing a cafeteria. Staff (users) browse the menu, build a cart, and place orders. Admins manage the menu, categories, users, and orders — all from a clean dark-themed dashboard.

---

## Table of Contents

1. [What It Does](#what-it-does)
2. [Features](#features)
3. [Tech Stack](#tech-stack)
4. [Code Structure](#code-structure)
5. [Architecture & Logic](#architecture--logic)
6. [Database Schema](#database-schema)
7. [Reusable Components](#reusable-components)
8. [Route Reference](#route-reference)
9. [How to Run](#how-to-run)

---

## What It Does

The Cafeteria Management System lets an organisation's staff order food and drinks from an internal cafeteria. Users log in with their company account, browse available products grouped by category, add items to a live cart, and submit an order tied to their room number. Admins receive all incoming orders, update their delivery status in real time, view per-user spending summaries (checks), and maintain the full product catalogue and user list.

---

## Features

### User-Facing
- **Login / Logout** — Session-based authentication with email + password validation.
- **Forgot Password** — Resets a user's password to a default value via email lookup.
- **Product Browsing** — Home page shows all available products in a responsive grid, filterable by category.
- **Shopping Cart** — Client-side cart (JavaScript) with quantity controls and a live running total.
- **Order Placement** — Submits the cart as a JSON payload via AJAX; supports a room number and optional notes. Admins can bill an order to any user.
- **My Orders** — Paginated list of the logged-in user's order history with status badges.
- **Order Details** — Breakdown of every item, quantity, unit price, and order total for a single order.
- **Order Cancellation** — Users can cancel their own orders.

### Admin-Facing
- **Admin Orders Dashboard** — Paginated list of all orders with filters (date, status, room number, user). Status can be updated live via AJAX without a page reload.
- **Checks Page** — Per-user spending summary (total amount owed) with date-range and user filters. Accordion drill-down reveals individual orders for each user. Paginated at 10 users per page.
- **Product Management** — Full CRUD: create, edit, delete products. Toggle availability on/off directly from the listing. Image upload supported.
- **Category Management** — Dedicated page to create and delete product categories. Deletion is blocked if any products are assigned to the category.
- **User Management** — Create, edit, and delete users with profile picture upload. Paginated listing.

### Cross-Cutting
- **Pagination** — Every admin listing (orders, products, users, checks) and the user's own orders page are paginated at 10 items per page, with filter-preserving URLs on filterable pages.
- **Role-Based Access** — The `/` root route serves the admin dashboard to admins and the product catalogue to regular users. Admin-only actions return 403 or redirect to login.
- **Flash Messages** — Session-based success/error banners on all forms.

---

## Tech Stack

| Layer | Technology |
| :--- | :--- |
| Language | PHP 8+ |
| Database | MySQL 8 (PDO, utf8mb4) |
| Frontend Styling | Tailwind CSS v4 (browser CDN) |
| Frontend Interactivity | Alpine.js v3 (accordion, toggles) |
| Custom JS | Vanilla JS (cart, client-side product filter) |
| Server options | PHP built-in server · XAMPP · WAMP · Apache/Nginx |

---

## Code Structure

```
cafeteria/
├── index.php                   # Entry point: session start, router bootstrap, all route definitions
│
├── utility/
│   ├── Router.php              # Regex-based HTTP router with method-spoofing support
│   ├── View.php                # View::render() and View::renderComponent() helpers
│   └── database.php            # PDO singleton; reads credentials from .env
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php      # Login, logout, forgot-password
│   │   ├── UserController.php      # User CRUD + home-page redirect
│   │   ├── OrderController.php     # Order placement, listing, status updates, checks
│   │   ├── ProductController.php   # Product CRUD + availability toggle
│   │   └── CategoryController.php  # Category CRUD
│   │
│   ├── models/
│   │   ├── Auth.php            # login(), emailExists(), updatePassword()
│   │   ├── User.php            # getAllUsers(), create(), update(), delete(), isAdmin()
│   │   ├── Order.php           # create(), getOrdersByUserId(), getOrdersWithItems(), getChecks(), etc.
│   │   └── Product.php         # getAll(), getAllAvailable(), add(), update(), delete(), category helpers
│   │
│   └── views/
│       ├── login.php           # Login form
│       ├── forgetpassword.php  # Forgot-password form
│       ├── home.php            # User product catalogue + cart
│       ├── my-orders.php       # User order history (paginated)
│       ├── order-details.php   # Single order breakdown
│       ├── users.php           # Admin user listing (paginated)
│       ├── admin/
│       │   ├── home.php        # Admin orders dashboard (paginated, filtered)
│       │   └── checks.php      # Admin checks / billing summary (paginated)
│       ├── products/
│       │   ├── index.php       # Product listing (paginated, client-side filter)
│       │   ├── create.php      # New product form
│       │   └── edit.php        # Edit product form
│       ├── categories/
│       │   └── index.php       # Category management (create + delete)
│       ├── users/
│       │   ├── add.php         # New user form
│       │   └── edit.php        # Edit user form
│       └── components/
│           ├── header.php      # Global nav bar (role-aware links, profile picture)
│           ├── footer.php      # Page footer
│           ├── pagination.php  # Reusable pagination controls component
│           ├── order_filter.php    # Admin order filter form
│           ├── order_panel.php     # Single order accordion panel
│           ├── products_grid.php   # Product card grid
│           └── userForm.php        # Shared user create/edit form fields
│
├── public/
│   └── uploads/                # User profile pictures and product images (web-accessible)
│
└── database/
    ├── schema.sql              # Clean CREATE TABLE statements
    └── cafeteria_backup.sql    # Full dump with schema + seed data
```

---

## Architecture & Logic

### MVC Pattern

The project follows a lightweight MVC pattern without a framework:

1. **`index.php`** — Boots the session, requires all controllers, registers every route, then calls `$router->resolve()`.
2. **`Router`** — Converts route paths like `/users/edit/:id` into regex patterns. On each request it matches the URL + HTTP method, then instantiates the right controller and calls the right method, passing URL parameters as arguments. HTTP method spoofing (`_method=DELETE` hidden field in POST forms) allows DELETE routes from HTML forms.
3. **Controllers** — Thin layer: validate input, call model methods, then either render a view or redirect. No business logic lives in controllers.
4. **Models** — All SQL lives here. Each model class uses `Database::getConnection()` to get a shared PDO singleton and runs prepared statements.
5. **Views** — Plain PHP templates. `View::render($name, $data)` extracts the data array as variables and `require`s the template. `View::renderComponent($name, $data)` does the same for files in `components/`.

### Authentication & Role Guard

- On login, `$_SESSION['userId']` is set to the user's database ID.
- `User::isAdmin()` fetches the current user's `role` column and returns `true` for `admin`.
- Every protected controller method checks `$_SESSION['userId']` at the top and redirects to `/login` if absent. Admin-only methods also check `User::isAdmin()`.

### Order Flow

1. User adds products to the JS cart on the home page.
2. On submit, vanilla JS serialises the cart to JSON and POSTs it to `/orders`.
3. `OrderController::store()` decodes the JSON, wraps the insert in a DB transaction (orders table + order_items table), and returns a JSON response.
4. The admin sees the order appear on the orders dashboard with status `processing`.
5. The admin clicks a status button; an AJAX PATCH request hits `/orders/status`, which validates the new status against an allowlist (`processing`, `out_for_delivery`, `done`, `cancelled`) and updates the DB row.

### Pagination

All paginated pages follow the same pattern in their controller method:

```php
$allItems  = Model::getAll();          // full dataset from DB
$perPage   = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalPages  = max(1, (int)ceil(count($allItems) / $perPage));
if ($currentPage > $totalPages) $currentPage = $totalPages;
$items = array_slice($allItems, ($currentPage - 1) * $perPage, $perPage);
```

The `pagination` component (`components/pagination.php`) is then called with:
- `$pagination` — array of `current_page` / `total_pages`
- `$baseUrl` — path for page links (e.g. `/products`)
- `$queryParams` — (optional) active filter values to preserve across page changes

The checks page passes `true` as the fourth argument to `array_slice` to **preserve integer keys**, which are user IDs referenced by Alpine.js accordion state.

### File Uploads

Profile pictures and product images are uploaded to `public/uploads/`. The controller validates the file extension against an allowlist (`jpg`, `jpeg`, `png`, `gif`), generates a time + uniqid-based filename to prevent collisions and path traversal, and stores only the filename in the database. The full URL is reconstructed at render time as `/public/uploads/{filename}`.

---

## Database Schema

| Table | Key Columns | Notes |
| :--- | :--- | :--- |
| `users` | `id`, `name`, `email`, `password_hash`, `room_no`, `extension`, `profile_pic`, `role` | `role` is `enum('admin','user')`, defaults to `user`. Password stored as bcrypt hash. |
| `categories` | `id`, `name` | `name` has a `UNIQUE` constraint. |
| `products` | `id`, `name`, `price`, `category_id`, `image`, `is_available` | `category_id` FK → `categories.id` (RESTRICT on delete). |
| `orders` | `id`, `user_id`, `room_no`, `notes`, `status`, `total_amount`, `created_at` | `status` is `enum('processing','out_for_delivery','done','cancelled')`. |
| `order_items` | `id`, `order_id`, `product_id`, `quantity`, `unit_price` | `order_id` FK → `orders.id` (CASCADE on delete). `unit_price` stored at time of order (price snapshot). |

---

## Reusable Components

All components live in `app/views/components/` and are included via `View::renderComponent($name, $data)`.

| Component | Purpose | Key Variables |
| :--- | :--- | :--- |
| `header.php` | Global navigation bar with role-aware menu links, profile picture, and mobile menu | Session-driven; reads current user automatically |
| `footer.php` | Page footer | — |
| `pagination.php` | Previous / page-number buttons / Next strip | `$pagination`, `$baseUrl`, `$queryParams` (optional) |
| `order_filter.php` | Admin filter form (date, status, room, user) | `$users`, `$rooms`, `$filters` |
| `order_panel.php` | Single order accordion card used on admin & user pages | `$order`, `$items` |
| `products_grid.php` | Responsive grid of product cards with Add-to-Cart buttons | `$products`, `$categories` |
| `userForm.php` | Shared form fields for add/edit user | `$user` (optional, for edit mode) |

---

## Route Reference

| Method | Path | Controller | Action |
| :--- | :--- | :--- | :--- |
| GET | `/login` | AuthController | Show login form |
| POST | `/login` | AuthController | Process login |
| GET | `/forgotpassword` | AuthController | Show forgot-password form |
| POST | `/forgotpassword` | AuthController | Reset password |
| GET | `/logout` | UserController | Destroy session, redirect |
| GET | `/` | UserController / OrderController | Home (catalogue for users, orders dashboard for admins) |
| GET | `/my-orders` | OrderController | User's order history (paginated) |
| GET | `/orders/show` | OrderController | Single order detail |
| POST | `/orders` | OrderController | Place a new order (JSON) |
| POST | `/orders/status` | OrderController | Update order status (JSON, admin) |
| POST | `/orders/cancel` | OrderController | Cancel an order |
| GET | `/checks` | OrderController | Admin billing/checks page (paginated) |
| GET | `/products` | ProductController | Product listing (paginated) |
| GET | `/products/create` | ProductController | New product form |
| POST | `/products` | ProductController | Save new product |
| GET | `/products/edit` | ProductController | Edit product form |
| POST | `/products/update` | ProductController | Save product edits |
| POST | `/products/availability` | ProductController | Toggle availability |
| DELETE | `/products/delete` | ProductController | Delete product + image |
| GET | `/categories` | CategoryController | Category management page |
| POST | `/categories` | CategoryController | Add a new category |
| DELETE | `/categories/delete` | CategoryController | Delete a category |
| GET | `/users` | UserController | User listing (paginated, admin) |
| GET | `/users/add` | UserController | New user form |
| POST | `/users` | UserController | Save new user |
| GET | `/users/edit/:id` | UserController | Edit user form |
| POST | `/users/update/:id` | UserController | Save user edits |
| GET | `/users/delete/:id` | UserController | Delete user |

---

## How to Run

### 1. Environment Configuration

Copy the example environment file and fill in your database credentials:

```bash
cp .env.example .env
```

Edit `.env`:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=cafeteria
DB_USER=your_mysql_username
DB_PASS=your_mysql_password
```

---

### 2. Database Setup

Import the schema and seed data into MySQL:

```bash
mysql -u your_username -p < database/cafeteria_backup.sql
```

Or log into MySQL and paste the contents of `database/cafeteria_backup.sql` manually. This creates the `cafeteria` database and all tables.

---

### 3. Upload Directory

Create the uploads folder and make it writable:

```bash
mkdir -p public/uploads
chmod 755 public/uploads
```

---

### 4. Start the Server

#### PHP Built-in Server (recommended for local development)

```bash
php -S localhost:8000
```

Visit `http://localhost:8000` in your browser.

#### XAMPP / WAMP

1. Copy the project folder into `htdocs/` (XAMPP) or `www/` (WAMP).
2. Start Apache and MySQL from the control panel.
3. Visit `http://localhost/cafeteria` in your browser.

---

### 5. Default Login Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| Admin | `admin@cafeteria.com` | `12345678` |
| User | `user@cafeteria.com` | `12345678` |

