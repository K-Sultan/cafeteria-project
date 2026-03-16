# Cafeteria Management System

A PHP-based cafeteria management system. Follow the steps below to set up the project on your local machine.

---

## Getting Started

### 1. Environment Configuration
The project uses a `.env` file to manage database credentials. 
1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Open `.env` and update the `DB_USER`, `DB_PASS`, and `DB_NAME` to match your local MySQL settings.

---

### 2. Database Setup

You need to import the database schema and seed data.

1. Open your terminal and log into your MySQL server:
```bash
mysql -u your_username -p
```


2. Once logged in, copy the contents of the file located at:
`database/cafeteria_backup.sql`
3. Paste the SQL code directly into your MySQL terminal. This will create the `cafeteria` database and the necessary tables automatically.

---

### 3. Folder Permissions

The system allows users to upload profile pictures. You must ensure the upload directory exists:

1. Create the `uploads` folder if it is missing:
```bash
mkdir -p public/uploads
```


2. Ensure the folder has write permissions (especially on Linux/Mac):
```bash
chmod 777 public/uploads
```



---

### 4. Running the Project

#### Using PHP Built-in Server (Recommended)

Navigate to the project root and run:

```bash
php -S localhost:8000
```

Then, visit `http://localhost:8000` in your browser.

#### Using XAMPP/WAMP

1. Move the project folder into your `htdocs` (XAMPP) or `www` (WAMP) directory.
2. Start the **Apache** and **MySQL** services from the control panel.
3. Visit `http://localhost/cafeteria-project` in your browser.

---
### 5. Default Login Credentials
Once the database is imported, you can use the following accounts to test the system:
### 5. Default Login Credentials
Once the database is imported, you can use the following accounts to test the system:

| Role | Email (Copiable) | Password (Copiable) |
| :--- | :--- | :--- |
| **Admin** | `admin@cafeteria.com` | `admin123` |
| **User** | `user@cafeteria.com` | `user123` |