<!-- file receiving data from the user from browser -->
<?php

require_once '../classes/login-contr.classes.php';
require_once '../classes/login.classes.php';
require_once '../classes/dbh.classes.php';

if(isset($_POST["submit"])){

    // Grabbing the data
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    // Instantiate LoginController class
    $loginController = new LoginController($uid, $pwd);

    // Run login
    $loginController->loginUser();

    // Redirect to home page after login
    header("location: ../home.php");
    exit();
}