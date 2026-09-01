# Login and Register System Using OOP PHP

A PHP and MySQL authentication project built with object-oriented programming. It demonstrates user registration, login, logout, form validation, session handling, password hashing/salting, and reusable PHP classes.

## Tech Stack

- PHP
- MySQL
- HTML / CSS
- PDO
- XAMPP or another PHP/MySQL local development environment

## Key Features

- User signup and registration
- User login and logout
- Session-based authentication
- Form and credential validation
- Password hashing / salting
- Database-backed user accounts
- Reusable OOP PHP classes
- Error handling for authentication and database operations

## Getting Started

### Prerequisites

Install a local PHP/MySQL environment such as XAMPP. Make sure Apache and MySQL are available before running the application.

### Clone the repository

```bash
git clone https://github.com/Arondith/LOGIN-SYSTEM-PHP-OOP.git
cd LOGIN-SYSTEM-PHP-OOP/LOGIN-SYSTEM-PHP-OOP
```

You can also download the repository as a ZIP file from GitHub.

### Configure the database connection

Open the database handler class and replace the placeholder connection values with your local MySQL credentials and database name.

Example PDO connection:

```php
protected function connect()
{
    try {
        $username = "YOUR_USERNAME";
        $password = "YOUR_PASSWORD";
        $dbh = new PDO(
            'mysql:host=YOUR_HOST;dbname=YOUR_DATABASE',
            $username,
            $password
        );

        return $dbh;
    } catch (PDOException $e) {
        print "Error! " . $e->getMessage() . "<br/>";
        die();
    }
}
```

> For local development, `YOUR_HOST` is commonly `localhost`. Do not commit real production credentials to the repository.

### Run with XAMPP

1. Copy or clone the repository into your XAMPP `htdocs` directory.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Configure the database connection used by the project.
4. Import or create the required database structure for the application.
5. Open the project in your browser through `http://localhost/` using the folder path where you placed the application.

## Project Structure

Important areas of the application include:

- `index.php` — main application entry point
- `home.php` — authenticated home page
- `reset_password.php` — password reset flow
- `classes/` — reusable OOP PHP classes
- `includes/` — shared application includes and configuration
- `assets/` and `css/` — front-end resources and styling

## Security Notes

This project is intended for learning and portfolio use. Before deploying it publicly, review database credentials, session configuration, password-reset behavior, error output, mail settings, and other environment-specific configuration.

## Original Author

**Natasha Tatenda Chirombe**

- GitHub: [@NATASHA-ct](https://github.com/NATASHA-ct)

## Acknowledgments

- Dani Krossing — tutorial/instruction reference

## License

See the repository files for applicable licensing information.
