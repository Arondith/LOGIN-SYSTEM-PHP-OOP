<?php
class Dbh {
    public function connect() {  // <- change this
        try {
            $username = "root"; // XAMPP default
            $password = "";     // XAMPP default
            $dbh = new PDO('mysql:host=localhost;dbname=ooplogin', $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
}