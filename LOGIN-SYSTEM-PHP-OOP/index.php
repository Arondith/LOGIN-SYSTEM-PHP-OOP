<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TITAN | Forge Your Path</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* ── RESET & BASE ───────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0a0a0a;
      --surface:   #111111;
      --surface2:  #161616;
      --border:    #222222;
      --border2:   #2e2e2e;
      --accent:    #e8c547;
      --accent-dim:#b89e36;
      --text:      #f0ede8;
      --muted:     #777;
      --muted2:    #555;
      --danger:    #e05252;
      --warning:   #e8a847;
      --success:   #52c97e;
      --radius:    6px;
      --font-head: 'Bebas Neue', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    html { scroll-behavior: smooth; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      font-weight: 300;
      line-height: 1.6;
      min-height: 100vh;
    }

    /* ── HEADER / NAV ───────────────────────────── */
    .topnav {
      position: sticky;
      top: 0;
      z-index: 100;
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
      gap: 2rem;
    }

    .nav-logo {
      font-family: var(--font-head);
      font-size: 2rem;
      letter-spacing: 0.12em;
      color: var(--accent);
      text-decoration: none;
      flex-shrink: 0;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 0.25rem;
      flex: 1;
    }

    .nav-links a {
      font-family: var(--font-body);
      font-size: 0.72rem;
      font-weight: 500;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--muted);
      text-decoration: none;
      padding: 0.4rem 0.75rem;
      border-radius: var(--radius);
      transition: color 0.2s, background 0.2s;
    }

    .nav-links a:hover { color: var(--text); background: var(--border); }

    .nav-member {
      list-style: none;
      display: flex;
      gap: 0.5rem;
      align-items: center;
      margin-left: auto;
    }

    .nav-member a {
      font-size: 0.72rem;
      font-weight: 500;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      text-decoration: none;
      padding: 0.45rem 1rem;
      border-radius: var(--radius);
      transition: all 0.2s;
    }

    .nav-member .btn-ghost {
      color: var(--muted);
      border: 1px solid var(--border);
    }
    .nav-member .btn-ghost:hover { color: var(--text); border-color: var(--muted); }

    .nav-member .btn-accent {
      color: var(--bg);
      background: var(--accent);
      border: 1px solid var(--accent);
      font-weight: 600;
    }
    .nav-member .btn-accent:hover { background: var(--accent-dim); border-color: var(--accent-dim); }

    .nav-member .welcome-msg {
      color: var(--accent);
      font-size: 0.72rem;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    /* ── BANNER ─────────────────────────────────── */
    .banner {
      position: relative;
      height: 420px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: var(--bg);
    }

    .banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(var(--border) 1px, transparent 1px),
        linear-gradient(90deg, var(--border) 1px, transparent 1px);
      background-size: 60px 60px;
      opacity: 0.5;
    }

    .banner::after {
      content: '';
      position: absolute;
      width: 600px;
      height: 300px;
      background: radial-gradient(ellipse, rgba(232,197,71,0.12) 0%, transparent 70%);
      border-radius: 50%;
    }

    .banner-content {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .banner-content h1 {
      font-family: var(--font-head);
      font-size: clamp(4rem, 10vw, 8rem);
      letter-spacing: 0.08em;
      line-height: 1;
      color: var(--text);
      animation: fadeUp 0.8s ease both;
    }

    .banner-content h1 span { color: var(--accent); }

    .banner-sub {
      margin-top: 1rem;
      font-size: 0.8rem;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--muted);
      animation: fadeUp 0.8s 0.15s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── MAIN SECTION ───────────────────────────── */
    .index-login {
      padding: 5rem 2rem;
      display: flex;
      justify-content: center;
    }

    .wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2px;
      max-width: 900px;
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
    }

    /* ── FORM PANELS ────────────────────────────── */
    .panel {
      background: var(--surface);
      padding: 2.5rem 2.5rem 3rem;
    }

    .panel-signup { border-right: 1px solid var(--border); }

    .panel-label {
      font-size: 0.65rem;
      font-weight: 500;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }

    .panel h4 {
      font-family: var(--font-head);
      font-size: 2rem;
      letter-spacing: 0.06em;
      color: var(--text);
      margin-bottom: 0.25rem;
    }

    .panel p {
      font-size: 0.82rem;
      color: var(--muted);
      margin-bottom: 1.75rem;
    }

    /* ── FORM ELEMENTS ──────────────────────────── */
    .form-group {
      position: relative;
      margin-bottom: 0.9rem;
    }

    .form-group label {
      display: block;
      font-size: 0.68rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 0.3rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"] {
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

    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(232,197,71,0.12);
    }

    input::placeholder { color: #3a3a3a; }

    /* Password strength */
    .strength-wrap { margin-top: 0.5rem; }

    #strength-bar-container {
      width: 100%;
      height: 3px;
      background: var(--border);
      border-radius: 2px;
      overflow: hidden;
    }

    #strength-bar {
      height: 100%;
      width: 0%;
      border-radius: 2px;
      transition: width 0.3s ease, background 0.3s ease;
    }

    #strength-message {
      font-size: 0.68rem;
      letter-spacing: 0.08em;
      margin-top: 0.3rem;
      font-weight: 500;
      text-transform: uppercase;
    }

    /* Buttons */
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
    }

    .btn-submit:hover  { background: var(--accent-dim); }
    .btn-submit:active { transform: scale(0.98); }

    .btn-login-submit {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
    }
    .btn-login-submit:hover { background: rgba(232,197,71,0.08); }

    /* ── FORGOT PASSWORD LINK ───────────────────── */
    .forgot-link {
      display: block;
      text-align: right;
      margin-top: -0.4rem;
      margin-bottom: 0.9rem;
      font-size: 0.7rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--muted2);
      text-decoration: none;
      cursor: pointer;
      transition: color 0.2s;
      background: none;
      border: none;
      font-family: var(--font-body);
      font-weight: 500;
      padding: 0;
    }
    .forgot-link:hover { color: var(--accent); }

    /* Two-col row */
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.75rem;
    }

    /* ── MODAL BACKDROP ─────────────────────────── */
    .modal-backdrop {
      position: fixed;
      inset: 0;
      z-index: 200;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(6px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }

    .modal-backdrop.open {
      opacity: 1;
      pointer-events: all;
    }

    /* ── MODAL BOX ──────────────────────────────── */
    .modal {
      position: relative;
      width: 100%;
      max-width: 440px;
      background: var(--surface);
      border: 1px solid var(--border2);
      border-radius: 10px;
      padding: 2.5rem 2.5rem 2.75rem;
      transform: translateY(18px) scale(0.98);
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
      opacity: 0;
    }

    .modal-backdrop.open .modal {
      transform: translateY(0) scale(1);
      opacity: 1;
    }

    /* Accent top bar */
    .modal::before {
      content: '';
      position: absolute;
      top: 0; left: 2rem; right: 2rem;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent), transparent);
      border-radius: 0 0 2px 2px;
    }

    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      padding: 0.3rem;
      line-height: 1;
      font-size: 1.2rem;
      transition: color 0.2s, transform 0.2s;
      border-radius: var(--radius);
    }
    .modal-close:hover { color: var(--text); transform: rotate(90deg); }

    .modal-label {
      font-size: 0.65rem;
      font-weight: 500;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 0.4rem;
    }

    .modal h4 {
      font-family: var(--font-head);
      font-size: 2rem;
      letter-spacing: 0.06em;
      color: var(--text);
      margin-bottom: 0.3rem;
    }

    .modal-desc {
      font-size: 0.82rem;
      color: var(--muted);
      margin-bottom: 1.75rem;
      line-height: 1.6;
    }

    /* Step indicator */
    .modal-steps {
      display: flex;
      gap: 0.4rem;
      margin-bottom: 1.75rem;
    }

    .modal-step {
      flex: 1;
      height: 3px;
      background: var(--border);
      border-radius: 2px;
      transition: background 0.4s ease;
    }

    .modal-step.active { background: var(--accent); }
    .modal-step.done   { background: var(--accent-dim); }

    /* Step panes */
    .step-pane {
      display: none;
      animation: fadeUp 0.3s ease both;
    }
    .step-pane.visible { display: block; }

    /* Inline alert */
    .modal-alert {
      padding: 0.65rem 0.9rem;
      border-radius: var(--radius);
      font-size: 0.8rem;
      margin-bottom: 1rem;
      display: none;
    }
    .modal-alert.error   { background: rgba(224,82,82,0.12); border: 1px solid rgba(224,82,82,0.3); color: var(--danger); display: block; }
    .modal-alert.success { background: rgba(82,201,126,0.10); border: 1px solid rgba(82,201,126,0.3); color: var(--success); display: block; }

    /* ── SUCCESS ICON ────────────────────────────── */
    .modal-success-icon {
      width: 56px;
      height: 56px;
      background: rgba(82,201,126,0.12);
      border: 1px solid rgba(82,201,126,0.3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.25rem;
    }

    .modal-success-icon svg {
      stroke: var(--success);
      animation: checkDraw 0.5s ease 0.1s both;
    }

    @keyframes checkDraw {
      from { stroke-dashoffset: 30; opacity: 0; }
      to   { stroke-dashoffset: 0;  opacity: 1; }
    }

    /* ── RESPONSIVE ─────────────────────────────── */
    @media (max-width: 700px) {
      .wrapper { grid-template-columns: 1fr; }
      .panel-signup { border-right: none; border-bottom: 1px solid var(--border); }
      .nav-links { display: none; }
      .modal { padding: 2rem 1.5rem 2.25rem; }
    }
  </style>
</head>
<body>

<!-- ── NAV ──────────────────────────────────────── -->
<header class="topnav">
  <nav class="nav">
    <a href="index.php" class="nav-logo">TITAN</a>

    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="#">Products</a></li>
      <li><a href="#">Current Sales</a></li>
      <li><a href="#">Member+</a></li>
    </ul>

    <ul class="nav-member">
      <?php if (isset($_SESSION['userid'])): ?>
        <li><span class="welcome-msg">Welcome, <?= htmlspecialchars($_SESSION['useruid']) ?></span></li>
        <li><a href="includes/logout.inc.php" class="btn-ghost">Log Out</a></li>
      <?php else: ?>
        <li><a href="#signup" class="btn-ghost">Sign Up</a></li>
        <li><a href="#login"  class="btn-accent">Log In</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<!-- ── BANNER ────────────────────────────────────── -->
<section class="banner">
  <div class="banner-content">
    <h1>Forge <span>Your</span> Path.</h1>
    <p class="banner-sub">Performance gear for those who never settle</p>
  </div>
</section>

<!-- ── AUTH SECTION ──────────────────────────────── -->
<section class="index-login">
  <div class="wrapper">

    <!-- SIGNUP -->
    <div class="panel panel-signup" id="signup">
      <div class="panel-label">New Here?</div>
      <h4>Sign Up</h4>
      <p>Create your TITAN account in seconds.</p>

      <form action="includes/signup.inc.php" method="post" id="signup-form" novalidate>

        <div class="form-row">
          <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="Jane" required>
          </div>
          <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
          </div>
        </div>

        <div class="form-group">
          <label for="middleName">Middle Name</label>
          <input type="text" id="middleName" name="middleName" placeholder="Optional">
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" placeholder="+63 912 345 6789"
                 pattern="[0-9+\s\-]{10,16}" title="Enter a valid phone number" required>
        </div>

        <div class="form-group">
          <label for="uid">Username</label>
          <input type="text" id="uid" name="uid" placeholder="titan_user" required autocomplete="username">
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="jane@example.com" required>
        </div>

        <div class="form-group">
          <label for="pwd">Password</label>
          <input type="password" id="pwd" name="pwd" placeholder="Create a strong password" required autocomplete="new-password">
          <div class="strength-wrap">
            <div id="strength-bar-container"><div id="strength-bar"></div></div>
            <div id="strength-message"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="pwdRepeat">Confirm Password</label>
          <input type="password" id="pwdRepeat" name="pwdRepeat" placeholder="Repeat your password" required autocomplete="new-password">
        </div>

        <button type="submit" name="submit" class="btn-submit">Create Account</button>
      </form>
    </div>

    <!-- LOGIN -->
    <div class="panel panel-login" id="login">
      <div class="panel-label">Welcome Back</div>
      <h4>Log In</h4>
      <p>Enter your credentials to access your account.</p>

      <form action="includes/login.inc.php" method="post" novalidate>
        <div class="form-group">
          <label for="login-uid">Username</label>
          <input type="text" id="login-uid" name="uid" placeholder="Your username" required autocomplete="username">
        </div>

        <div class="form-group">
          <label for="login-pwd">Password</label>
          <input type="password" id="login-pwd" name="pwd" placeholder="Your password" required autocomplete="current-password">
        </div>

        <!-- Forgot password trigger -->
        <button type="button" class="forgot-link" id="forgot-trigger">Forgot password?</button>

        <button type="submit" name="submit" class="btn-submit btn-login-submit">Log In</button>
      </form>
    </div>

  </div>
</section>

<!-- ── FORGOT PASSWORD MODAL ─────────────────────── -->
<div class="modal-backdrop" id="forgot-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div class="modal">
    <button class="modal-close" id="modal-close" aria-label="Close">&times;</button>

    <!-- Step progress bar -->
    <div class="modal-steps" aria-hidden="true">
      <div class="modal-step active"  id="step-dot-1"></div>
      <div class="modal-step"         id="step-dot-2"></div>
      <div class="modal-step"         id="step-dot-3"></div>
    </div>

    <!-- ── STEP 1: Enter email ── -->
    <div class="step-pane visible" id="step-1">
      <div class="modal-label">Account Recovery</div>
      <h4 id="modal-title">Forgot Password</h4>
      <p class="modal-desc">Enter the email address linked to your account and we'll send you a reset link.</p>

      <div id="step1-alert" class="modal-alert"></div>

      <form id="forgot-form" novalidate>
        <div class="form-group">
          <label for="forgot-email">Email Address</label>
          <input type="email" id="forgot-email" name="forgot-email" placeholder="jane@example.com" required autocomplete="email">
        </div>
        <button type="submit" class="btn-submit">Send Reset Link</button>
      </form>
    </div>

    <!-- ── STEP 2: Check your inbox ── -->
    <div class="step-pane" id="step-2">
      <div class="modal-label">Email Sent</div>
      <h4>Check Your Inbox</h4>
      <p class="modal-desc">
        We sent a password reset link to <strong id="sent-to-email" style="color:var(--text)"></strong>.
        The link will expire in <strong style="color:var(--accent)">15 minutes</strong>.
      </p>

      <p class="modal-desc" style="font-size:0.78rem; color:var(--muted2)">
        Didn't receive it? Check your spam folder or
        <button type="button" id="resend-btn" style="background:none;border:none;color:var(--accent);cursor:pointer;font-family:var(--font-body);font-size:0.78rem;padding:0;text-decoration:underline">resend the email</button>.
      </p>

      <div id="resend-feedback" class="modal-alert"></div>

      <button type="button" class="btn-submit btn-login-submit" id="done-btn">Back to Login</button>
    </div>

    <!-- ── STEP 3: Confirmation (used after password reset redirect) ── -->
    <div class="step-pane" id="step-3">
      <div class="modal-success-icon">
        <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
             style="stroke-dasharray:30; stroke-dashoffset:30">
          <polyline points="5,13 10,19 21,7"/>
        </svg>
      </div>
      <div class="modal-label">All Done</div>
      <h4>Password Reset</h4>
      <p class="modal-desc">Your password has been updated successfully. You can now log in with your new credentials.</p>
      <button type="button" class="btn-submit btn-login-submit" id="confirmed-close-btn">Return to Login</button>
    </div>

  </div>
</div>

<!-- ── SCRIPTS ───────────────────────────────────── -->
<script>
  /* ── Password strength meter ── */
  const pwdInput = document.getElementById('pwd');
  const bar      = document.getElementById('strength-bar');
  const barMsg   = document.getElementById('strength-message');

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
    if (v.length >= 8)    score++;
    if (v.length >= 12)   score++;
    if (/[A-Z]/.test(v))  score++;
    if (/[a-z]/.test(v))  score++;
    if (/[0-9]/.test(v))  score++;
    if (/[\W_]/.test(v))  score++;

    const capped = Math.min(score, levels.length - 1);
    const lvl    = levels[capped];
    const pct    = ((capped + 1) / levels.length) * 100;

    bar.style.width      = v.length ? pct + '%' : '0%';
    bar.style.background = lvl.color;
    barMsg.textContent   = v.length ? lvl.label : '';
    barMsg.style.color   = lvl.labelColor;
  });

  /* ── Confirm password validation ── */
  const signupForm = document.getElementById('signup-form');
  const pwdRepeat  = document.getElementById('pwdRepeat');

  signupForm.addEventListener('submit', (e) => {
    if (pwdInput.value !== pwdRepeat.value) {
      e.preventDefault();
      pwdRepeat.setCustomValidity("Passwords don't match.");
      pwdRepeat.reportValidity();
    } else {
      pwdRepeat.setCustomValidity('');
    }
  });

  /* ── Smooth scroll for anchor nav links ── */
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', e => {
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ─────────────────────────────────────────────
     FORGOT PASSWORD MODAL
  ───────────────────────────────────────────── */
  const backdrop   = document.getElementById('forgot-modal');
  const trigger    = document.getElementById('forgot-trigger');
  const closeBtn   = document.getElementById('modal-close');
  const forgotForm = document.getElementById('forgot-form');

  const stepDots   = [
    document.getElementById('step-dot-1'),
    document.getElementById('step-dot-2'),
    document.getElementById('step-dot-3'),
  ];

  const stepPanes  = [
    document.getElementById('step-1'),
    document.getElementById('step-2'),
    document.getElementById('step-3'),
  ];

  let currentStep = 0;

  function openModal() {
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
    // Reset to step 1
    goToStep(0);
    document.getElementById('forgot-email').value = '';
    document.getElementById('step1-alert').className = 'modal-alert';
    document.getElementById('step1-alert').textContent = '';
    setTimeout(() => document.getElementById('forgot-email').focus(), 300);
  }

  function closeModal() {
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  function goToStep(index) {
    stepPanes.forEach((p, i) => {
      p.classList.toggle('visible', i === index);
    });
    stepDots.forEach((d, i) => {
      d.classList.toggle('active', i === index);
      d.classList.toggle('done',   i < index);
    });
    currentStep = index;
  }

  /* Open / close triggers */
  trigger.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);

  backdrop.addEventListener('click', (e) => {
    if (e.target === backdrop) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && backdrop.classList.contains('open')) closeModal();
  });

  /* ── Step 1: submit email ── */
  forgotForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const emailInput = document.getElementById('forgot-email');
    const alertBox   = document.getElementById('step1-alert');

    alertBox.className  = 'modal-alert';
    alertBox.textContent = '';

    const email = emailInput.value.trim();

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alertBox.className   = 'modal-alert error';
      alertBox.textContent = 'Please enter a valid email address.';
      emailInput.focus();
      return;
    }

    /* Disable button while processing */
    const submitBtn = forgotForm.querySelector('.btn-submit');
    submitBtn.disabled    = true;
    submitBtn.textContent = 'Sending…';

    try {
      /* POST to your server-side handler */
      const res = await fetch('includes/forgot_password.inc.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}`
      });

      const data = await res.json();

      if (data.success) {
        document.getElementById('sent-to-email').textContent = email;
        goToStep(1);
      } else {
        alertBox.className   = 'modal-alert error';
        alertBox.textContent = data.message || 'Something went wrong. Please try again.';
      }
    } catch {
      /* Network / server error — show step 2 anyway for privacy (don't leak whether email exists) */
      document.getElementById('sent-to-email').textContent = email;
      goToStep(1);
    } finally {
      submitBtn.disabled    = false;
      submitBtn.textContent = 'Send Reset Link';
    }
  });

  /* ── Step 2: resend button ── */
  document.getElementById('resend-btn').addEventListener('click', async () => {
    const email    = document.getElementById('sent-to-email').textContent;
    const feedback = document.getElementById('resend-feedback');

    feedback.className   = 'modal-alert';
    feedback.textContent = '';

    try {
      const res = await fetch('includes/forgot_password.inc.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}`
      });
      feedback.className   = 'modal-alert success';
      feedback.textContent = 'Email resent! Check your inbox.';
    } catch {
      feedback.className   = 'modal-alert error';
      feedback.textContent = 'Could not resend. Please try again.';
    }
  });

  /* ── Step 2: done button ── */
  document.getElementById('done-btn').addEventListener('click', closeModal);

  /* ── Step 3: confirmed close (used when redirected back after reset) ── */
  document.getElementById('confirmed-close-btn').addEventListener('click', closeModal);

  /* ── Auto-open step 3 if redirected back with ?reset=success ── */
  if (new URLSearchParams(location.search).get('reset') === 'success') {
    openModal();
    goToStep(2);
  }
</script>

</body>
</html>