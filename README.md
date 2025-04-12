# task managmet System

## Introduction

This project is a full-stack task management application built with Laravel. It features separate interfaces for regular users and administrators, along with role-based permissions and a clean, responsive design.

## Project Overview

The application provides a comprehensive task management system with the following key features:
- User Authentication: Secure registration and login system with role-based access control
- Task Management: Create, edit, delete, and track tasks with completion status
- Dual Interfaces: User interface built with Laravel Blade and Tailwind CSS and Admin dashboard powered by Filament Admin Panel
- Data Protection: Form request validation for all inputs
- Database Management: MySQL database with migrations and seeders
- Soft Delete: Trash and restore functionality for tasks


## Technology Used

- Backend: Laravel 12.x
- Admin Panel: Filament Admin Panel
- Frontend: Blade templates with Tailwind CSS
- Authentication: Custom authentication with Sanctum token with custom middleware for role management
- Database: MySQL with migrations and Eloquent ORM
- Version Control: Git with feature-based commits

## Installation and Usage

### Getting Started
Follow the setup instructions below to get the application running on your local environment.

## Prerequisites
- PHP 8.1+
- Composer
- MySQL
- Node.js and NPM


### Running the Project

1. Clone the repository to your local machine using `git clone https://github.com/dina1033/task-managment.git`.
2. Enter to the folder `cd task-management`.
3. install the required dependencies by running `composer install`.
4. Install NPM packages `npm install`.
5. Create a copy of the `.env.example` file and name it `.env` run`cp .env.example .env`.
6. Generate Laravel application key run `php artisan key:generate`.
7. Run the migrations and seed the database using `php artisan migrate:fresh --seed`.
8. Create another database for unit testing environment and put the information in `phpunit.xml` file.
9. Compile assets `npm run dev`.
9. Start the server `php artisan serve`.

### Running the Test Cases

1. Run the test cases using `php artisan test`.

### Access Information

- User Interface: http://localhost:8000
- Admin Panel: http://localhost:8000/admin
- Default Admin Login: Email: admin@example.com , Password: password
- Default User Login: Email: user@example.com , Password: password
