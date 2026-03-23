<?php
require_once '../classes/dbh.classes.php';

if (isset($_POST['submit'])) {

    // Grab data from form safely
    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $phone = trim($_POST['phone']);
    $uid = trim($_POST['uid']);
    $email = trim($_POST['email']);
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwdRepeat'];

    // Basic validation
    if ($pwd !== $pwdRepeat) {
        header("location: ../index.php?error=passwordsdontmatch");
        exit();
    }

    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        header("location: ../index.php?error=invalidphone");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location: ../index.php?error=invalidemail");
        exit();
    }

    // Hash password
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    try {
        // Connect to DB
        $dbh = new Dbh();
        $conn = $dbh->connect();

        // Check if username or email already exists
        $checkStmt = $conn->prepare("SELECT users_uid, users_email FROM users WHERE users_uid = ? OR users_email = ?");
        $checkStmt->execute([$uid, $email]);

        if ($checkStmt->rowCount() > 0) {
            header("location: ../index.php?error=userexists");
            exit();
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (firstName, middleName, lastName, phone, users_uid, users_email, users_pwd) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $middleName, $lastName, $phone, $uid, $email, $hashedPwd]);

        // Success
        $stmt = null;
        header("location: ../index.php?signup=success");
        exit();

    } catch (PDOException $e) {
        // DB error
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

} else {
    // If user navigates directly
    header("location: ../index.php");
    exit();
}