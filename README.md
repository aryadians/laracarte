<p align="center">
  <img src="https://api.iconify.design/mdi/silverware-fork-knife.svg?color=%234f46e5&width=120&height=120" alt="LaraCarte Logo">
</p>

<h1 align="center">LaraCarte - Restaurant Management System</h1>

<p align="center">
  A modern and intuitive restaurant management system built with the TALL stack.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4.svg?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20.svg?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Livewire-3-4d55d2.svg?style=for-the-badge&logo=livewire" alt="Livewire 3">
  <img src="https://img.shields.io/badge/Volt-1.7-8b5cf6.svg?style=for-the-badge" alt="Volt">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
</p>
<p align="center">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

---

## 🚀 About LaraCarte

LaraCarte is a powerful and easy-to-use restaurant management system designed to streamline your restaurant's operations. Built with the latest web technologies, it provides a seamless experience for managing your products, tables, and orders.

## ✨ Key Features

| Feature              | Description                                                                 |
| -------------------- | --------------------------------------------------------------------------- |
| 📦 Product Management | Add, edit, and categorize your menu items with ease.                        |
| 🍽️ Table Management  | Visually organize your restaurant's floor plan and table status.            |
| 🛒 Order Processing   | A fast and intuitive interface for taking and managing customer orders.     |
| 📈 Admin Dashboard   | Get real-time insights into your sales, popular products, and more.         |
| 🔒 Secure Auth       | Role-based access control for administrators and staff.                     |
| 🎨 Modern UI         | A stunning and responsive UI built with Tailwind CSS and Alpine.js.         |

## 📸 Screenshots

*(Add some screenshots of your application here)*

![Screenshot 1](https://via.placeholder.com/800x450.png?text=LaraCarte+Dashboard)
![Screenshot 2](https://via.placeholder.com/800x450.png?text=Order+Management)

## 🛠️ Tech Stack

-   **Backend:** [Laravel](https://laravel.com/), [PHP](https://www.php.net/)
-   **Frontend:** [Livewire](https://livewire.laravel.com/), [Volt](https://volt.laravel.com/), [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
-   **Database:** MySQL / PostgreSQL / SQLite (configurable)

## 🏁 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   A web server (like Nginx or Apache) or use the built-in Laravel server.

### Installation

1.  **Clone the repository**
    ```sh
    git clone https://github.com/aryadians/laracarte.git
    cd laracarte
    ```

2.  **Install dependencies**
    Run the setup script to install all PHP and JS dependencies, create your `.env` file, and generate the application key.
    ```sh
    composer run-script setup
    ```

3.  **Run Migrations**
    Set up your database in the `.env` file, then run the migrations and seed the database with initial data.
    ```sh
    php artisan migrate --seed
    ```

4.  **Start the development server**
    This will start the PHP server, queue worker, and Vite dev server.
    ```sh
    composer run-script dev
    ```

5.  You can now access the application at `http://127.0.0.1:8000`.
    -   **Admin Email:** `admin@laracarte.com`
    -   **Password:** `password`

## 🤝 Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## 📄 License

Distributed under the MIT License.
