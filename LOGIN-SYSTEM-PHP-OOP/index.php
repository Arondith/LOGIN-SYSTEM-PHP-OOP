<?php
session_start();

$errorCode = $_GET['error'] ?? '';
$messages = [
  'emptyinput' => 'Please complete all required fields.',
  'invaliduid' => 'Choose a username using letters and numbers only.',
  'invalidemail' => 'Please enter a valid email address.',
  'passwordsdontmatch' => 'Your passwords do not match.',
  'stmtfailed' => 'Something went wrong. Please try again.',
  'usernametaken' => 'That username or email is already in use.',
  'wronglogin' => 'Incorrect username or password.',
];
$authMessage = $messages[$errorCode] ?? '';
$signupErrors = ['emptyinput', 'invaliduid', 'invalidemail', 'passwordsdontmatch', 'stmtfailed', 'usernametaken'];
$initialTab = in_array($errorCode, $signupErrors, true) ? 'signup' : 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#090909">
  <title>TITAN | Forge Your Path</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    * { margin: 0; }

    :root {
      --bg: #080808;
      --surface: #101010;
      --surface-2: #151515;
      --surface-3: #1b1b1b;
      --line: rgba(255,255,255,.09);
      --line-strong: rgba(255,255,255,.15);
      --text: #f7f4ec;
      --muted: #96938c;
      --muted-2: #686660;
      --gold: #e5bf52;
      --gold-2: #b79535;
      --danger: #ff6b6b;
      --success: #64d58a;
      --radius: 22px;
      --radius-sm: 12px;
      --shadow: 0 30px 80px rgba(0,0,0,.45);
      --font-display: 'Bebas Neue', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    html { scroll-behavior: smooth; }
    body {
      min-height: 100vh;
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      line-height: 1.5;
      overflow-x: hidden;
    }

    button, input { font: inherit; }
    button, a { -webkit-tap-highlight-color: transparent; }

    .page-shell {
      min-height: 100vh;
      background:
        radial-gradient(circle at 10% 10%, rgba(229,191,82,.08), transparent 28%),
        radial-gradient(circle at 90% 85%, rgba(229,191,82,.05), transparent 26%),
        var(--bg);
    }

    .topbar {
      height: 76px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 0 clamp(1.25rem, 4vw, 4rem);
      border-bottom: 1px solid var(--line);
      background: rgba(8,8,8,.72);
      backdrop-filter: blur(18px);
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: .7rem;
      color: var(--text);
      text-decoration: none;
    }

    .brand-mark {
      width: 34px;
      height: 34px;
      display: grid;
      place-items: center;
      border: 1px solid rgba(229,191,82,.45);
      border-radius: 10px;
      background: rgba(229,191,82,.08);
      color: var(--gold);
      font-family: var(--font-display);
      font-size: 1.25rem;
      letter-spacing: .03em;
    }

    .brand-name {
      font-family: var(--font-display);
      font-size: 1.8rem;
      letter-spacing: .14em;
    }

    .topbar-note {
      display: flex;
      align-items: center;
      gap: .5rem;
      color: var(--muted);
      font-size: .76rem;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .status-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--success);
      box-shadow: 0 0 0 5px rgba(100,213,138,.08);
    }

    .auth-layout {
      width: min(1180px, calc(100% - 2rem));
      min-height: calc(100vh - 76px);
      margin: 0 auto;
      padding: clamp(1rem, 3vw, 2.75rem) 0;
      display: grid;
      grid-template-columns: minmax(0, 1.08fr) minmax(390px, .92fr);
      align-items: stretch;
      gap: 1.2rem;
    }

    .visual-panel,
    .auth-card {
      min-height: 650px;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .visual-panel {
      position: relative;
      padding: clamp(1.7rem, 4vw, 3rem);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      isolation: isolate;
      background:
        linear-gradient(180deg, rgba(7,7,7,.18), rgba(7,7,7,.84)),
        linear-gradient(90deg, rgba(7,7,7,.78), rgba(7,7,7,.12)),
        url('assets/Titan.jpg') center/cover no-repeat;
    }

    .visual-panel::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: -1;
      background:
        linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
      background-size: 48px 48px;
      mask-image: linear-gradient(to bottom, rgba(0,0,0,.7), transparent 80%);
    }

    .visual-kicker {
      display: inline-flex;
      align-items: center;
      gap: .55rem;
      width: fit-content;
      padding: .55rem .8rem;
      border: 1px solid rgba(255,255,255,.16);
      border-radius: 999px;
      background: rgba(8,8,8,.45);
      backdrop-filter: blur(14px);
      color: #dedbd4;
      font-size: .69rem;
      font-weight: 600;
      letter-spacing: .14em;
      text-transform: uppercase;
    }

    .visual-kicker span {
      color: var(--gold);
      font-size: .85rem;
    }

    .visual-copy { max-width: 650px; }

    .visual-copy h1 {
      max-width: 680px;
      font-family: var(--font-display);
      font-size: clamp(4.2rem, 8vw, 7.6rem);
      font-weight: 400;
      line-height: .86;
      letter-spacing: .035em;
      text-wrap: balance;
      text-shadow: 0 6px 32px rgba(0,0,0,.45);
    }

    .visual-copy h1 em {
      color: var(--gold);
      font-style: normal;
    }

    .visual-copy > p {
      max-width: 560px;
      margin-top: 1.1rem;
      color: #c6c2b9;
      font-size: clamp(.93rem, 1.5vw, 1.08rem);
      font-weight: 300;
    }

    .visual-meta {
      margin-top: 2rem;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      border: 1px solid rgba(255,255,255,.13);
      border-radius: 16px;
      overflow: hidden;
      background: rgba(8,8,8,.5);
      backdrop-filter: blur(14px);
    }

    .meta-item {
      padding: 1rem 1.1rem;
      border-right: 1px solid rgba(255,255,255,.1);
    }
    .meta-item:last-child { border-right: 0; }

    .meta-value {
      font-family: var(--font-display);
      color: var(--gold);
      font-size: 1.45rem;
      letter-spacing: .05em;
    }

    .meta-label {
      margin-top: .12rem;
      color: #a9a59d;
      font-size: .68rem;
      text-transform: uppercase;
      letter-spacing: .1em;
    }

    .auth-card {
      background: rgba(16,16,16,.92);
      backdrop-filter: blur(20px);
      display: flex;
      flex-direction: column;
    }

    .auth-head {
      padding: 2rem 2rem 1.35rem;
      border-bottom: 1px solid var(--line);
    }

    .auth-eyebrow {
      color: var(--gold);
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .17em;
      text-transform: uppercase;
    }

    .auth-head h2 {
      margin-top: .35rem;
      font-family: var(--font-display);
      font-size: 2.8rem;
      line-height: 1;
      letter-spacing: .04em;
      font-weight: 400;
    }

    .auth-head p {
      margin-top: .55rem;
      color: var(--muted);
      font-size: .88rem;
    }

    .tab-list {
      margin-top: 1.35rem;
      padding: 4px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px;
      background: #0b0b0b;
      border: 1px solid var(--line);
      border-radius: 12px;
    }

    .tab-btn {
      border: 0;
      border-radius: 9px;
      padding: .68rem 1rem;
      background: transparent;
      color: var(--muted);
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      cursor: pointer;
      transition: .2s ease;
    }

    .tab-btn.active {
      background: var(--surface-3);
      color: var(--text);
      box-shadow: inset 0 0 0 1px var(--line);
    }

    .auth-body {
      flex: 1;
      padding: 1.6rem 2rem 2rem;
      overflow: auto;
    }

    .auth-pane { display: none; animation: fadeIn .25s ease; }
    .auth-pane.active { display: block; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(7px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .alert {
      margin-bottom: 1rem;
      padding: .8rem .9rem;
      border: 1px solid rgba(255,107,107,.28);
      border-radius: 10px;
      background: rgba(255,107,107,.075);
      color: #ff9a9a;
      font-size: .78rem;
    }

    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .85rem;
    }

    .field { margin-bottom: .9rem; }

    .field label {
      display: block;
      margin: 0 0 .38rem .1rem;
      color: #b5b1aa;
      font-size: .68rem;
      font-weight: 600;
      letter-spacing: .09em;
      text-transform: uppercase;
    }

    .input-wrap { position: relative; }

    .field input {
      width: 100%;
      min-height: 48px;
      border: 1px solid var(--line);
      border-radius: 11px;
      background: #0b0b0b;
      color: var(--text);
      outline: none;
      padding: .78rem .9rem;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }

    .field input:hover { border-color: var(--line-strong); }
    .field input:focus {
      border-color: rgba(229,191,82,.72);
      box-shadow: 0 0 0 4px rgba(229,191,82,.09);
      background: #0d0d0d;
    }

    .field input::placeholder { color: #4e4c48; }

    .password-input { padding-right: 3.5rem !important; }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: .55rem;
      transform: translateY(-50%);
      border: 0;
      background: transparent;
      color: var(--muted-2);
      padding: .35rem .45rem;
      cursor: pointer;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
    }
    .password-toggle:hover { color: var(--gold); }

    .form-actions {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      margin: -.2rem 0 .85rem;
    }

    .text-button {
      border: 0;
      background: transparent;
      color: var(--muted);
      padding: .2rem 0;
      cursor: pointer;
      font-size: .74rem;
      text-decoration: none;
    }
    .text-button:hover { color: var(--gold); }

    .primary-btn {
      width: 100%;
      min-height: 50px;
      border: 1px solid var(--gold);
      border-radius: 11px;
      background: var(--gold);
      color: #0a0a0a;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      cursor: pointer;
      box-shadow: 0 12px 28px rgba(229,191,82,.12);
      transition: transform .15s, background .2s, border-color .2s;
    }
    .primary-btn:hover { background: #f0ca61; border-color: #f0ca61; transform: translateY(-1px); }
    .primary-btn:active { transform: translateY(0); }
    .primary-btn:disabled { opacity: .55; cursor: wait; transform: none; }

    .form-footnote {
      margin-top: 1rem;
      color: var(--muted-2);
      font-size: .7rem;
      line-height: 1.55;
      text-align: center;
    }

    .strength { margin: .45rem .1rem 0; }
    .strength-track {
      height: 3px;
      background: #242424;
      border-radius: 999px;
      overflow: hidden;
    }
    .strength-bar { width: 0; height: 100%; transition: width .25s, background .25s; }
    .strength-label { margin-top: .28rem; min-height: 1em; color: var(--muted-2); font-size: .63rem; letter-spacing: .07em; text-transform: uppercase; }

    .modal-backdrop {
      position: fixed;
      inset: 0;
      z-index: 100;
      display: grid;
      place-items: center;
      padding: 1rem;
      background: rgba(0,0,0,.76);
      backdrop-filter: blur(10px);
      opacity: 0;
      visibility: hidden;
      transition: opacity .2s, visibility .2s;
    }
    .modal-backdrop.open { opacity: 1; visibility: visible; }

    .modal {
      width: min(440px, 100%);
      border: 1px solid var(--line-strong);
      border-radius: 20px;
      background: #111;
      box-shadow: var(--shadow);
      padding: 1.6rem;
      transform: translateY(12px) scale(.98);
      transition: transform .22s;
    }
    .modal-backdrop.open .modal { transform: translateY(0) scale(1); }

    .modal-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; }
    .modal-close {
      width: 34px;
      height: 34px;
      border: 1px solid var(--line);
      border-radius: 9px;
      background: #171717;
      color: var(--muted);
      cursor: pointer;
      font-size: 1.2rem;
    }
    .modal-close:hover { color: var(--text); border-color: var(--line-strong); }
    .modal h3 { font-family: var(--font-display); font-size: 2rem; letter-spacing: .04em; font-weight: 400; }
    .modal-copy { margin: .4rem 0 1.2rem; color: var(--muted); font-size: .82rem; }
    .modal-alert { display: none; margin-bottom: .8rem; padding: .75rem .85rem; border-radius: 10px; font-size: .76rem; }
    .modal-alert.error { display: block; color: #ff9a9a; border: 1px solid rgba(255,107,107,.28); background: rgba(255,107,107,.07); }
    .modal-alert.success { display: block; color: #9ae6b1; border: 1px solid rgba(100,213,138,.25); background: rgba(100,213,138,.07); }
    .modal-step { display: none; }
    .modal-step.active { display: block; animation: fadeIn .25s ease; }
    .sent-email { color: var(--gold); font-weight: 600; }

    @media (max-width: 900px) {
      .auth-layout { grid-template-columns: 1fr; max-width: 660px; }
      .visual-panel { min-height: 430px; }
      .auth-card { min-height: auto; }
      .visual-copy h1 { font-size: clamp(4rem, 14vw, 6rem); }
    }

    @media (max-width: 560px) {
      .topbar { height: 66px; padding: 0 1rem; }
      .topbar-note { display: none; }
      .auth-layout { width: calc(100% - 1rem); min-height: calc(100vh - 66px); padding: .5rem 0 1rem; gap: .7rem; }
      .visual-panel, .auth-card { border-radius: 17px; }
      .visual-panel { min-height: 360px; padding: 1.3rem; }
      .visual-meta { grid-template-columns: 1fr; }
      .meta-item { border-right: 0; border-bottom: 1px solid rgba(255,255,255,.1); padding: .65rem .85rem; display: flex; justify-content: space-between; align-items: center; }
      .meta-item:last-child { border-bottom: 0; }
      .auth-head, .auth-body { padding-left: 1.25rem; padding-right: 1.25rem; }
      .field-row { grid-template-columns: 1fr; gap: 0; }
    }
  </style>
</head>
<body data-initial-tab="<?= htmlspecialchars($initialTab) ?>">
<div class="page-shell">
  <header class="topbar">
    <a class="brand" href="index.php" aria-label="TITAN home">
      <span class="brand-mark">T</span>
      <span class="brand-name">TITAN</span>
    </a>
    <div class="topbar-note"><span class="status-dot"></span> Secure member access</div>
  </header>

  <main class="auth-layout">
    <section class="visual-panel" aria-label="TITAN brand feature">
      <div class="visual-kicker"><span>◆</span> Built for relentless progress</div>

      <div class="visual-copy">
        <h1>Earn Your <em>Edge.</em></h1>
        <p>One secure account for your TITAN profile, membership, orders and exclusive member access.</p>
        <div class="visual-meta">
          <div class="meta-item"><div class="meta-value">01</div><div class="meta-label">Secure login</div></div>
          <div class="meta-item"><div class="meta-value">24/7</div><div class="meta-label">Account access</div></div>
          <div class="meta-item"><div class="meta-value">T+</div><div class="meta-label">Member benefits</div></div>
        </div>
      </div>
    </section>

    <section class="auth-card" aria-label="Account access">
      <div class="auth-head">
        <div class="auth-eyebrow">Member Portal</div>
        <h2>Access TITAN</h2>
        <p>Sign in to continue, or create an account to get started.</p>

        <div class="tab-list" role="tablist" aria-label="Account forms">
          <button class="tab-btn" id="login-tab" type="button" data-tab="login" role="tab">Log In</button>
          <button class="tab-btn" id="signup-tab" type="button" data-tab="signup" role="tab">Create Account</button>
        </div>
      </div>

      <div class="auth-body">
        <div class="auth-pane" id="login-pane" data-pane="login" role="tabpanel">
          <?php if ($authMessage && $initialTab === 'login'): ?>
            <div class="alert" role="alert"><?= htmlspecialchars($authMessage) ?></div>
          <?php endif; ?>

          <form action="includes/login.inc.php" method="post">
            <div class="field">
              <label for="login-uid">Username or Email</label>
              <input id="login-uid" type="text" name="uid" placeholder="Enter your username" autocomplete="username" required>
            </div>

            <div class="field">
              <label for="login-pwd">Password</label>
              <div class="input-wrap">
                <input class="password-input" id="login-pwd" type="password" name="pwd" placeholder="Enter your password" autocomplete="current-password" required>
                <button class="password-toggle" type="button" data-password-target="login-pwd">Show</button>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="text-button" id="forgot-trigger">Forgot password?</button>
            </div>

            <button class="primary-btn" type="submit" name="submit">Log In</button>
            <div class="form-footnote">Your credentials are transmitted to your PHP authentication handler and passwords are verified against stored hashes.</div>
          </form>
        </div>

        <div class="auth-pane" id="signup-pane" data-pane="signup" role="tabpanel">
          <?php if ($authMessage && $initialTab === 'signup'): ?>
            <div class="alert" role="alert"><?= htmlspecialchars($authMessage) ?></div>
          <?php endif; ?>

          <form action="includes/signup.inc.php" method="post" id="signup-form">
            <div class="field-row">
              <div class="field">
                <label for="firstName">First Name</label>
                <input id="firstName" type="text" name="firstName" placeholder="Jane" autocomplete="given-name" required>
              </div>
              <div class="field">
                <label for="lastName">Last Name</label>
                <input id="lastName" type="text" name="lastName" placeholder="Doe" autocomplete="family-name" required>
              </div>
            </div>

            <div class="field">
              <label for="middleName">Middle Name</label>
              <input id="middleName" type="text" name="middleName" placeholder="Optional" autocomplete="additional-name">
            </div>

            <div class="field-row">
              <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" type="tel" name="phone" placeholder="+63 912 345 6789" pattern="[0-9+\s\-]{10,16}" autocomplete="tel" required>
              </div>
              <div class="field">
                <label for="uid">Username</label>
                <input id="uid" type="text" name="uid" placeholder="titan_user" autocomplete="username" required>
              </div>
            </div>

            <div class="field">
              <label for="email">Email Address</label>
              <input id="email" type="email" name="email" placeholder="jane@example.com" autocomplete="email" required>
            </div>

            <div class="field">
              <label for="pwd">Password</label>
              <div class="input-wrap">
                <input class="password-input" id="pwd" type="password" name="pwd" placeholder="Create a strong password" autocomplete="new-password" required>
                <button class="password-toggle" type="button" data-password-target="pwd">Show</button>
              </div>
              <div class="strength">
                <div class="strength-track"><div class="strength-bar" id="strength-bar"></div></div>
                <div class="strength-label" id="strength-label"></div>
              </div>
            </div>

            <div class="field">
              <label for="pwdRepeat">Confirm Password</label>
              <div class="input-wrap">
                <input class="password-input" id="pwdRepeat" type="password" name="pwdRepeat" placeholder="Repeat your password" autocomplete="new-password" required>
                <button class="password-toggle" type="button" data-password-target="pwdRepeat">Show</button>
              </div>
            </div>

            <button class="primary-btn" type="submit" name="submit">Create Account</button>
            <div class="form-footnote">Use a unique password of at least 8 characters. Your server-side signup flow remains unchanged.</div>
          </form>
        </div>
      </div>
    </section>
  </main>
</div>

<div class="modal-backdrop" id="forgot-modal" role="dialog" aria-modal="true" aria-labelledby="forgot-title">
  <div class="modal">
    <div class="modal-step active" id="forgot-step-1">
      <div class="modal-top">
        <div>
          <div class="auth-eyebrow">Account Recovery</div>
          <h3 id="forgot-title">Reset Password</h3>
        </div>
        <button class="modal-close" type="button" data-close-modal aria-label="Close">×</button>
      </div>
      <p class="modal-copy">Enter the email linked to your account. If it exists, TITAN will send a password reset link.</p>
      <div class="modal-alert" id="forgot-alert"></div>
      <form id="forgot-form">
        <div class="field">
          <label for="forgot-email">Email Address</label>
          <input id="forgot-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
        </div>
        <button class="primary-btn" type="submit">Send Reset Link</button>
      </form>
    </div>

    <div class="modal-step" id="forgot-step-2">
      <div class="modal-top">
        <div>
          <div class="auth-eyebrow">Email Sent</div>
          <h3>Check Your Inbox</h3>
        </div>
        <button class="modal-close" type="button" data-close-modal aria-label="Close">×</button>
      </div>
      <p class="modal-copy">If an account is associated with <span class="sent-email" id="sent-email"></span>, a reset link has been sent. Check your inbox and spam folder.</p>
      <button class="primary-btn" type="button" data-close-modal>Back to Login</button>
    </div>

    <div class="modal-step" id="forgot-step-3">
      <div class="modal-top">
        <div>
          <div class="auth-eyebrow">Success</div>
          <h3>Password Updated</h3>
        </div>
        <button class="modal-close" type="button" data-close-modal aria-label="Close">×</button>
      </div>
      <p class="modal-copy">Your password was reset successfully. You can now sign in with your new credentials.</p>
      <button class="primary-btn" type="button" data-close-modal>Continue to Login</button>
    </div>
  </div>
</div>

<script>
  const body = document.body;
  const tabButtons = document.querySelectorAll('[data-tab]');
  const panes = document.querySelectorAll('[data-pane]');

  function activateTab(name, updateHash = true) {
    tabButtons.forEach(btn => {
      const active = btn.dataset.tab === name;
      btn.classList.toggle('active', active);
      btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    panes.forEach(pane => pane.classList.toggle('active', pane.dataset.pane === name));
    if (updateHash) history.replaceState(null, '', '#' + name);
  }

  tabButtons.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));

  const hashTab = location.hash === '#signup' ? 'signup' : location.hash === '#login' ? 'login' : null;
  activateTab(hashTab || body.dataset.initialTab || 'login', false);

  document.querySelectorAll('[data-password-target]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.passwordTarget);
      const showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      button.textContent = showing ? 'Show' : 'Hide';
    });
  });

  const pwd = document.getElementById('pwd');
  const pwdRepeat = document.getElementById('pwdRepeat');
  const strengthBar = document.getElementById('strength-bar');
  const strengthLabel = document.getElementById('strength-label');

  pwd.addEventListener('input', () => {
    const value = pwd.value;
    let score = 0;
    if (value.length >= 8) score++;
    if (value.length >= 12) score++;
    if (/[A-Z]/.test(value)) score++;
    if (/[a-z]/.test(value)) score++;
    if (/\d/.test(value)) score++;
    if (/[^A-Za-z0-9]/.test(value)) score++;

    const percent = value ? Math.max(16, Math.round((score / 6) * 100)) : 0;
    const label = score <= 2 ? 'Weak' : score <= 4 ? 'Good' : 'Strong';
    const color = score <= 2 ? '#ff6b6b' : score <= 4 ? '#e5bf52' : '#64d58a';

    strengthBar.style.width = percent + '%';
    strengthBar.style.background = color;
    strengthLabel.textContent = value ? label + ' password' : '';
    strengthLabel.style.color = color;
  });

  document.getElementById('signup-form').addEventListener('submit', event => {
    pwdRepeat.setCustomValidity('');
    if (pwd.value !== pwdRepeat.value) {
      event.preventDefault();
      pwdRepeat.setCustomValidity('Passwords do not match.');
      pwdRepeat.reportValidity();
    }
  });
  pwdRepeat.addEventListener('input', () => pwdRepeat.setCustomValidity(''));

  const modal = document.getElementById('forgot-modal');
  const forgotTrigger = document.getElementById('forgot-trigger');
  const forgotForm = document.getElementById('forgot-form');
  const forgotAlert = document.getElementById('forgot-alert');
  const step1 = document.getElementById('forgot-step-1');
  const step2 = document.getElementById('forgot-step-2');
  const step3 = document.getElementById('forgot-step-3');

  function showModalStep(step) {
    [step1, step2, step3].forEach(item => item.classList.remove('active'));
    step.classList.add('active');
  }

  function openForgotModal() {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    showModalStep(step1);
    forgotAlert.className = 'modal-alert';
    forgotAlert.textContent = '';
    setTimeout(() => document.getElementById('forgot-email').focus(), 150);
  }

  function closeForgotModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  forgotTrigger.addEventListener('click', openForgotModal);
  document.querySelectorAll('[data-close-modal]').forEach(button => button.addEventListener('click', closeForgotModal));
  modal.addEventListener('click', event => { if (event.target === modal) closeForgotModal(); });
  document.addEventListener('keydown', event => { if (event.key === 'Escape') closeForgotModal(); });

  forgotForm.addEventListener('submit', async event => {
    event.preventDefault();
    const emailInput = document.getElementById('forgot-email');
    const email = emailInput.value.trim();
    const submit = forgotForm.querySelector('button[type="submit"]');

    forgotAlert.className = 'modal-alert';
    forgotAlert.textContent = '';

    if (!emailInput.checkValidity()) {
      emailInput.reportValidity();
      return;
    }

    submit.disabled = true;
    submit.textContent = 'Sending...';

    try {
      const response = await fetch('includes/forgot_password.inc.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(email)
      });

      let data = {};
      try { data = await response.json(); } catch (_) {}

      if (!response.ok && data.message) {
        forgotAlert.className = 'modal-alert error';
        forgotAlert.textContent = data.message;
        return;
      }

      document.getElementById('sent-email').textContent = email;
      showModalStep(step2);
    } catch (_) {
      document.getElementById('sent-email').textContent = email;
      showModalStep(step2);
    } finally {
      submit.disabled = false;
      submit.textContent = 'Send Reset Link';
    }
  });

  if (new URLSearchParams(location.search).get('reset') === 'success') {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    showModalStep(step3);
  }
</script>
</body>
</html>
