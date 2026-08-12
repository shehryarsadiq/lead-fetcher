# LeadFetcher

A web-based lead generation and management system designed to collect, organize, and manage business leads efficiently.

## 📌 About The Project

**LeadFetcher** is a web-based lead management application built to help users collect and manage business leads in an organized way.

The system provides a clean interface for managing lead information and maintaining lead records through a centralized dashboard.

## ✨ Features

* 🔐 Admin authentication
* 📊 Dashboard with lead overview
* 👥 Lead management
* ➕ Add new leads
* ✏️ Edit existing leads
* 🗑️ Delete leads
* 🔍 Search and manage lead records
* 📋 Organized lead information
* 📱 Responsive user interface
* 🗄️ MySQL database integration
* ⚡ Dynamic interactions using JavaScript/AJAX

## 🛠️ Technologies Used

* **PHP 8+**
* **MySQL**
* **MySQLi**
* **HTML5**
* **CSS3**
* **Bootstrap**
* **JavaScript**
* **AJAX / Fetch API**
* **Font Awesome**
* **Git & GitHub**

## 📸 Screenshots

### Dashboard

![Dashboard](screenshots/dashboard.png)

### Lead Management

![Lead Management](screenshots/leads.png)

### Add Lead

![Add Lead](screenshots/add-lead.png)

### Login

![Login](screenshots/login.png)

> Replace the screenshot filenames above with the exact filenames inside your `screenshots` folder.

## ⚙️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR-USERNAME/lead-fetcher.git
```

### 2. Move the Project

Move the project folder into your local server directory.

For WAMP:

```text
C:\wamp64\www\
```

For XAMPP:

```text
C:\xampp\htdocs\
```

### 3. Create the Database

Open **phpMyAdmin** and create a new MySQL database.

Example:

```text
lead_fetcher
```

### 4. Import Database

Import the provided:

```text
database.sql
```

file into your newly created database.

### 5. Configure Database Connection

Open your database configuration file and update the connection details according to your local environment.

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "lead_fetcher";
```

### 6. Run the Project

Start **Apache** and **MySQL** from WAMP/XAMPP.

Then open:

```text
http://localhost/lead-fetcher/
```

## 📂 Project Structure

```text
lead-fetcher/
│
├── admin/
├── assets/
├── config/
├── includes/
├── screenshots/
├── database.sql
├── index.php
└── ...
```

## 🎯 Purpose

This project was developed as a practical full-stack web development project to demonstrate skills in:

* Backend development
* Database management
* CRUD operations
* Authentication
* Responsive frontend development
* PHP & MySQL integration
* JavaScript/AJAX development

## 🚀 Future Improvements

Some planned improvements include:

* Advanced lead filtering
* Lead status management
* Lead assignment
* Follow-up reminders
* Export leads to Excel/PDF
* Email integration
* Analytics and reporting
* API integrations

## 👨‍💻 Developer

**Shehryar Siddiqui**

Full Stack Web Developer | PHP Developer | Shopify Developer

---

⭐ If you find this project useful, consider giving it a star.
