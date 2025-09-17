# Marketplace Web Application
A modern online marketplace built with Laravel 10 and Tailwind CSS.

## Features
* User Authentication & Profiles (Admin, Seller, Client)
* Product Management (CRUD for sellers)
* Product Catalogue with Filters
* Shopping Cart Functionality
* Order Management & Status Updates
* Product Reviews & Ratings (for clients)
* Admin Panel for user and content moderation

## Requirements
* Composer
* Node.js & NPM
* PHP >= 8.2
* MySQL / MariaDB or another relational database
* Local server environment (e.g., Nginx, Apache)

## Installation

1.  Clone the repository:
    ```bash
    git clone https://github.com/Mr-No-bo-dy/Marketplace_laravel
    ```

2. Navigate to projects folder:
    ```bash
    cd Marketplace_laravel
    ```

3.  Install dependencies:
    ```bash
    composer install
    npm install
    ```

4.  Configure your environment:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  Configure your database in the `.env` file:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_db_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6.  Run database migrations and seed data:
    ```bash
    php artisan migrate:fresh --seed
    ```

7.  Link storage directory:
    ```bash
    php artisan storage:link
    ```

## Running the Application:
* Start the development server:
    ```bash
    php artisan serve
    ```

* The application will be available at http://127.0.0.1:8000

## Default users credentials (for testing)
* Admin: admin@example.com - admin_password
* Seller: seller@example.com - seller_password
* Client: client@example.com - client_password

## License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
