# PHP OOP Login System

## About

A PHP and MySQL authentication system built using **object-oriented programming (OOP)** principles. The project demonstrates a complete user-authentication flow with a **landing page, registration, login, logout, authenticated home page, session handling, password reset, form validation, and database-backed user accounts**. Passwords are protected using **salted/hashed storage instead of plain-text credentials**, while reusable PHP classes separate database access, authentication logic, validation, and application behavior.

The project was created as a hands-on implementation of secure login-system fundamentals, showing how a front-end authentication interface connects to PHP classes and a MySQL database while maintaining user sessions and handling common authentication errors.

## Core features

- User registration / signup
- Login page and authenticated landing/home page
- Logout functionality
- MySQL database-backed user accounts
- Salted / hashed password storage
- Session creation and persistence after authentication
- Password-reset flow
- Form and credential validation
- Error handling for authentication and database operations
- Reusable PHP classes following OOP structure
- Shared configuration and application includes
- Responsive front-end assets and styling

## Project location

The application source is in:

`LOGIN-SYSTEM-PHP-OOP/`

Main areas include:

- `index.php` — application entry point
- `home.php` — authenticated home page
- `reset_password.php` — password reset flow
- `classes/` — reusable PHP classes
- `includes/` — shared includes and configuration
- `assets/` and `css/` — front-end resources

A more detailed project README is available inside the application folder:

`LOGIN-SYSTEM-PHP-OOP/README.md`

## Technology

- PHP
- MySQL
- HTML / CSS
- Object-Oriented Programming
- Sessions and authentication
- Password hashing / salting

## Running locally

1. Install XAMPP or another PHP/MySQL development environment.
2. Place the repository in your web server document root.
3. Start Apache and MySQL.
4. Review the project configuration and database settings under the application folder.
5. Open the project through `http://localhost/` in your browser.

## Notes

This is an academic/software project. Review database credentials, mail settings, and environment-specific configuration before using it outside a local development environment.
