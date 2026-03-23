<?php
date_default_timezone_set('Asia/Manila');
/**
 * TITAN — Reset Password Page
 * Validates token from URL and lets user set a new password.
 */

session_start();

// ── DB CONFIG ─────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'ooplogin');
define('DB_USER', 'root');
define('DB_PASS', '');

function db_connect(): PDO {
    try {
        return new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        error_log('TITAN DB connect error: ' . $e->getMessage());
        die('A server error occurred.');
    }
}

$token   = trim($_GET['token'] ?? '');
$error   = '';
$success = false;

// ── DEBUG: token comparison ────────────────────────────────────────

// ── Validate token on load ────────────────────────────────────────
$validToken = false;
if ($token) {
    $pdo  = db_connect();
    $stmt = $pdo->prepare(
        "SELECT users_id FROM password_resets
         WHERE token = ? AND expires_at > NOW() LIMIT 1"
    );
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) $validToken = true;
}

// ── Handle form submission ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $newPwd    = $_POST['new_pwd']    ?? '';
    $repeatPwd = $_POST['repeat_pwd'] ?? '';

    if (strlen($newPwd) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($newPwd !== $repeatPwd) {
        $error = 'Passwords do not match.';
    } else {
        $pdo  = db_connect();
        $hash = password_hash($newPwd, PASSWORD_BCRYPT);

        $upd = $pdo->prepare(
            "UPDATE users SET users_pwd = ? WHERE users_id = (
                SELECT users_id FROM password_resets WHERE token = ? LIMIT 1
             )"
        );
        $upd->execute([$hash, $token]);

        $del = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $del->execute([$token]);

        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TITAN | Reset Password</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0a0a0a;
      --surface:   #111111;
      --border:    #222222;
      --border2:   #2e2e2e;
      --accent:    #e8c547;
      --accent-dim:#b89e36;
      --text:      #f0ede8;
      --muted:     #777;
      --danger:    #e05252;
      --warning:   #e8a847;
      --success:   #52c97e;
      --radius:    6px;
      --font-head: 'Bebas Neue', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      font-weight: 300;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .topnav {
      background: rgba(10,10,10,0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
    }
    .nav {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      height: 64px;
      display: flex;
      align-items: center;
    }
    .nav-logo {
      font-family: var(--font-head);
      font-size: 2rem;
      letter-spacing: 0.12em;
      color: var(--accent);
      text-decoration: none;
    }

    .page-body {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4rem 1.5rem;
      position: relative;
    }

    .page-body::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 60px 60px;
      opacity: 0.3;
      pointer-events: none;
    }

    .page-body::after {
      content: '';
      position: absolute;
      width: 500px;
      height: 250px;
      background: radial-gradient(ellipse, rgba(232,197,71,0.08) 0%, transparent 70%);
      pointer-events: none;
    }

    .card {
      position: relative;
      z-index: 1;
      background: var(--surface);
      border: 1px solid var(--border2);
      border-radius: 10px;
      padding: 2.75rem 2.5rem 3rem;
      width: 100%;
      max-width: 440px;
      animation: fadeUp 0.5s ease both;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0; left: 2rem; right: 2rem;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent), transparent);
      border-radius: 0 0 2px 2px;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card-label {
      font-size: 0.65rem;
      font-weight: 500;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 0.4rem;
    }

    .card h2 {
      font-family: var(--font-head);
      font-size: 2.2rem;
      letter-spacing: 0.06em;
      color: var(--text);
      margin-bottom: 0.25rem;
    }

    .card-desc {
      font-size: 0.82rem;
      color: var(--muted);
      margin-bottom: 1.75rem;
    }

    .form-group { margin-bottom: 0.9rem; }

    .form-group label {
      display: block;
      font-size: 0.68rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 0.3rem;
    }

    input[type="password"] {
      width: 100%;
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text);
      font-family: var(--font-body);
      font-size: 0.88rem;
      padding: 0.65rem 0.9rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input[type="password"]:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(232,197,71,0.12);
    }

    input::placeholder { color: #3a3a3a; }

    .strength-wrap { margin-top: 0.5rem; }
    #strength-bar-container { width: 100%; height: 3px; background: var(--border); border-radius: 2px; overflow: hidden; }
    #strength-bar { height: 100%; width: 0%; border-radius: 2px; transition: width 0.3s, background 0.3s; }
    #strength-message { font-size: 0.68rem; letter-spacing: 0.08em; margin-top: 0.3rem; font-weight: 500; text-transform: uppercase; }

    .alert { padding: 0.65rem 0.9rem; border-radius: var(--radius); font-size: 0.8rem; margin-bottom: 1rem; }
    .alert-error   { background: rgba(224,82,82,0.12); border: 1px solid rgba(224,82,82,0.3); color: var(--danger); }
    .alert-success { background: rgba(82,201,126,0.10); border: 1px solid rgba(82,201,126,0.3); color: var(--success); }

    .btn-submit {
      width: 100%;
      margin-top: 1.25rem;
      padding: 0.8rem;
      background: var(--accent);
      color: var(--bg);
      border: none;
      border-radius: var(--radius);
      font-family: var(--font-head);
      font-size: 1.1rem;
      letter-spacing: 0.15em;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
      display: block;
      text-align: center;
      text-decoration: none;
    }
    .btn-submit:hover  { background: var(--accent-dim); }
    .btn-submit:active { transform: scale(0.98); }

    .btn-ghost {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
    }
    .btn-ghost:hover { background: rgba(232,197,71,0.08); }

    .success-icon {
      width: 60px; height: 60px;
      background: rgba(82,201,126,0.10);
      border: 1px solid rgba(82,201,126,0.3);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.5rem;
    }
    .success-icon svg {
      stroke: var(--success);
      stroke-dasharray: 30;
      stroke-dashoffset: 30;
      animation: checkDraw 0.5s ease 0.2s forwards;
    }
    @keyframes checkDraw { to { stroke-dashoffset: 0; } }

    .expired-icon {
      width: 60px; height: 60px;
      background: rgba(224,82,82,0.10);
      border: 1px solid rgba(224,82,82,0.3);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.5rem;
    }
    .expired-icon svg { stroke: var(--danger); }

    @media (max-width: 500px) { .card { padding: 2rem 1.5rem 2.25rem; } }
  </style>
</head>
<body>

<header class="topnav">
  <nav class="nav">
    <a href="index.php" class="nav-logo">TITAN</a>
  </nav>
</header>

<main class="page-body">
  <div class="card">

    <?php if ($success): ?>
      <div class="success-icon">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="5,14 11,20 23,8"/>
        </svg>
      </div>
      <div class="card-label" style="text-align:center">Success</div>
      <h2 style="text-align:center">Password Updated</h2>
      <p class="card-desc" style="text-align:center; margin-bottom:1.5rem">
        Your password has been changed. You can now log in with your new credentials.
      </p>
      <a href="index.php?reset=success#login" class="btn-submit btn-ghost">Back to Login</a>

    <?php elseif (!$token || !$validToken): ?>
      <div class="expired-icon">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="7" y1="7" x2="21" y2="21"/>
          <line x1="21" y1="7" x2="7" y2="21"/>
        </svg>
      </div>
      <div class="card-label" style="text-align:center">Link Expired</div>
      <h2 style="text-align:center">Invalid Reset Link</h2>
      <p class="card-desc" style="text-align:center; margin-bottom:1.5rem">
        This link is invalid or has expired. Reset links are only valid for
        <strong style="color:var(--accent)">15 minutes</strong>.
        Please request a new one.
      </p>
      <a href="index.php#login" class="btn-submit">Request New Link</a>

    <?php else: ?>
      <div class="card-label">Account Recovery</div>
      <h2>New Password</h2>
      <p class="card-desc">Choose a strong password for your TITAN account.</p>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" id="reset-form" novalidate>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="form-group">
          <label for="new_pwd">New Password</label>
          <input type="password" id="new_pwd" name="new_pwd"
                 placeholder="Create a strong password" required autocomplete="new-password">
          <div class="strength-wrap">
            <div id="strength-bar-container"><div id="strength-bar"></div></div>
            <div id="strength-message"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="repeat_pwd">Confirm Password</label>
          <input type="password" id="repeat_pwd" name="repeat_pwd"
                 placeholder="Repeat your password" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-submit">Set New Password</button>
      </form>
    <?php endif; ?>

  </div>
</main>

<script>
  const pwdInput = document.getElementById('new_pwd');
  if (pwdInput) {
    const bar    = document.getElementById('strength-bar');
    const barMsg = document.getElementById('strength-message');

    const levels = [
      { color: 'var(--danger)',  label: 'Weak',     labelColor: 'var(--danger)'  },
      { color: 'var(--danger)',  label: 'Weak',     labelColor: 'var(--danger)'  },
      { color: 'var(--warning)', label: 'Fair',     labelColor: 'var(--warning)' },
      { color: 'var(--warning)', label: 'Moderate', labelColor: 'var(--warning)' },
      { color: 'var(--success)', label: 'Strong',   labelColor: 'var(--success)' },
      { color: 'var(--success)', label: 'Strong',   labelColor: 'var(--success)' },
    ];

    pwdInput.addEventListener('input', () => {
      const v = pwdInput.value;
      let score = 0;
      if (v.length >= 8)   score++;
      if (v.length >= 12)  score++;
      if (/[A-Z]/.test(v)) score++;
      if (/[a-z]/.test(v)) score++;
      if (/[0-9]/.test(v)) score++;
      if (/[\W_]/.test(v)) score++;

      const capped = Math.min(score, levels.length - 1);
      const lvl    = levels[capped];
      const pct    = ((capped + 1) / levels.length) * 100;

      bar.style.width      = v.length ? pct + '%' : '0%';
      bar.style.background = lvl.color;
      barMsg.textContent   = v.length ? lvl.label : '';
      barMsg.style.color   = lvl.labelColor;
    });

    const form      = document.getElementById('reset-form');
    const repeatPwd = document.getElementById('repeat_pwd');

    form.addEventListener('submit', (e) => {
      if (pwdInput.value !== repeatPwd.value) {
        e.preventDefault();
        repeatPwd.setCustomValidity("Passwords don't match.");
        repeatPwd.reportValidity();
      } else {
        repeatPwd.setCustomValidity('');
      }
    });
  }
</script>

</body>
</html>