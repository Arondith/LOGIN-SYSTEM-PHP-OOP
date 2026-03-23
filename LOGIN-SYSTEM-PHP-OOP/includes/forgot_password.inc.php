<?php
date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 1);
error_reporting(E_ALL);



session_start();
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../src/Exception.php';
require_once __DIR__ . '/../src/PHPMailer.php';
require_once __DIR__ . '/../src/SMTP.php';

// ── DB CONFIG ─────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'ooplogin');
define('DB_USER', 'root');
define('DB_PASS', '');

// ── MAIL CONFIG ───────────────────────────────────────────────────
define('MAIL_FROM',      'no-reply@titan.com');
define('MAIL_FROM_NAME', 'TITAN');
define('SITE_URL',       'http://localhost/LOGIN-SYSTEM-PHP-OOP'); // No trailing slash

// ── Mailtrap SMTP ─────────────────────────────────────────────────
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'aronditee@gmail.com');  // ← from Mailtrap dashboard
define('SMTP_PASS', 'jqzt vmkn hong smkg');  // ← from Mailtrap dashboard
define('SMTP_PORT', 587);

// ── TOKEN TTL ─────────────────────────────────────────────────────
define('TOKEN_TTL_MINUTES', 600);

// ─────────────────────────────────────────────────────────────────

function json_out(bool $success, string $message): void {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(false, 'Invalid request.');
}

$email = trim($_POST['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(false, 'Please provide a valid email address.');
}

// ── DB CONNECTION ─────────────────────────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log('TITAN DB connect error: ' . $e->getMessage());
    json_out(false, 'A server error occurred. Please try again later.');
}

// ── LOOK UP USER ──────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT users_id, users_uid FROM users WHERE users_email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // ── CREATE RESET TOKEN ────────────────────────────────────────
    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + TOKEN_TTL_MINUTES * 60);

    // ── STORE TOKEN ───────────────────────────────────────────────
    $ins = $pdo->prepare(
        "INSERT INTO password_resets (users_id, token, expires_at, created_at)
         VALUES (?, ?, ?, NOW())
         ON DUPLICATE KEY UPDATE token      = VALUES(token),
                                  expires_at = VALUES(expires_at),
                                  created_at = NOW()"
    );
    $ins->execute([$user['users_id'], $token, $expires]);

    // ── SEND EMAIL ────────────────────────────────────────────────
    $resetLink = SITE_URL . '/reset_password.php?token=' . urlencode($token);
   $username = htmlspecialchars($user['users_uid']);
    $ttl       = TOKEN_TTL_MINUTES;

    $body = "Hi {$username},\r\n\r\n"
          . "We received a request to reset your TITAN account password.\r\n\r\n"
          . "Click the link below to set a new password (valid for {$ttl} minutes):\r\n\r\n"
          . "{$resetLink}\r\n\r\n"
          . "If you didn't request this, you can safely ignore this email.\r\n\r\n"
          . "— The TITAN Team";

  $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true,
    ]
];
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // ← changed
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($email);
        $mail->Subject = 'TITAN — Reset Your Password';
        $mail->Body    = $body;
        $mail->CharSet = 'UTF-8';
        $mail->send();
   } catch (Exception $e) {
    json_out(false, 'Mailer error: ' . $mail->ErrorInfo);
}
}
// Always return success (privacy: don't reveal if email is registered)
json_out(true, 'If that email exists in our system, a reset link has been sent.');