<?php
require_once 'dbh.classes.php';

class Login extends Dbh {

    protected function getUser($uid, $pwd) {

        // 1️⃣ Fetch user by username or email
        $stmt = $this->connect()->prepare('SELECT * FROM users WHERE users_uid = ? OR users_email = ?;');
        if (!$stmt->execute(array($uid, $uid))) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        // 2️⃣ Check if user exists
        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: ../index.php?error=usernotfound");
            exit();
        }

        // 3️⃣ Get user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4️⃣ Verify password
        if (!password_verify($pwd, $user["users_pwd"])) {
            $stmt = null;
            header("location: ../index.php?error=wrongpassword");
            exit();
        }

        // 5️⃣ Start session with user data
        session_start();
        $_SESSION["userid"] = $user["users_id"];
        $_SESSION["useruid"] = $user["users_uid"];

        $stmt = null;
    }
}