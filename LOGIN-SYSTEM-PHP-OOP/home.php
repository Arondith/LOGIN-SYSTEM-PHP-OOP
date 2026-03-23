<?php
session_start();

if (!isset($_SESSION["userid"])) {
    header("location: index.php");
    exit();
}

$username = htmlspecialchars($_SESSION["useruid"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TITAN | Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* ── RESET & BASE ───────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0a0a0a;
      --surface:   #111111;
      --border:    #222222;
      --accent:    #e8c547;
      --accent-dim:#b89e36;
      --text:      #f0ede8;
      --muted:     #666;
      --radius:    6px;
      --sidebar-w: 240px;
      --font-head: 'Bebas Neue', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      font-weight: 300;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── SIDEBAR ────────────────────────────────── */
    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: var(--sidebar-w);
      height: 100%;
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      z-index: 200;
      transform: translateX(calc(-1 * var(--sidebar-w)));
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.active { transform: translateX(0); }

    .sidebar-header {
      padding: 1.5rem 1.5rem 1rem;
      border-bottom: 1px solid var(--border);
    }

    .sidebar-logo {
      font-family: var(--font-head);
      font-size: 1.8rem;
      letter-spacing: 0.12em;
      color: var(--accent);
    }

    .sidebar-tagline {
      font-size: 0.65rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--muted);
      margin-top: 0.1rem;
    }

    .sidebar-nav {
      flex: 1;
      padding: 1rem 0;
    }

    .sidebar-section-label {
      font-size: 0.6rem;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--muted);
      padding: 0.75rem 1.5rem 0.35rem;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.7rem 1.5rem;
      color: #888;
      text-decoration: none;
      font-size: 0.84rem;
      font-weight: 400;
      letter-spacing: 0.04em;
      border-left: 2px solid transparent;
      transition: color 0.2s, background 0.2s, border-color 0.2s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      color: var(--text);
      background: rgba(232,197,71,0.06);
      border-left-color: var(--accent);
    }

    .sidebar a .icon {
      width: 16px;
      text-align: center;
      opacity: 0.7;
      font-style: normal;
    }

    .sidebar-footer {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--border);
    }

    .sidebar-footer a {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      color: var(--muted);
      text-decoration: none;
      font-size: 0.8rem;
      letter-spacing: 0.06em;
      transition: color 0.2s;
    }

    .sidebar-footer a:hover { color: #e05252; }

    /* ── OVERLAY ────────────────────────────────── */
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.6);
      z-index: 150;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s;
    }

    .overlay.active { opacity: 1; pointer-events: all; }

    /* ── TOP NAV ────────────────────────────────── */
    .topnav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(10,10,10,0.9);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      height: 64px;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      gap: 1rem;
    }

    .menu-toggle {
      background: var(--surface);
      border: 1px solid var(--border);
      color: var(--text);
      width: 36px;
      height: 36px;
      border-radius: var(--radius);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: background 0.2s, border-color 0.2s;
      font-size: 1rem;
    }

    .menu-toggle:hover {
      background: var(--border);
      border-color: #333;
    }

    .topnav-logo {
      font-family: var(--font-head);
      font-size: 1.6rem;
      letter-spacing: 0.12em;
      color: var(--accent);
      text-decoration: none;
    }

    .topnav-spacer { flex: 1; }

    .topnav-user {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 0.78rem;
      color: var(--muted);
      letter-spacing: 0.06em;
    }

    .topnav-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-head);
      font-size: 1rem;
      color: var(--bg);
      letter-spacing: 0;
      flex-shrink: 0;
    }

    /* ── HERO BANNER ────────────────────────────── */
    .banner {
      position: relative;
      height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
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
      width: 500px;
      height: 250px;
      background: radial-gradient(ellipse, rgba(232,197,71,0.1) 0%, transparent 70%);
      border-radius: 50%;
    }

    .banner-content {
      position: relative;
      z-index: 1;
      text-align: center;
      animation: fadeUp 0.7s ease both;
    }

    .banner-content h1 {
      font-family: var(--font-head);
      font-size: clamp(3rem, 8vw, 6.5rem);
      letter-spacing: 0.08em;
      line-height: 1;
    }

    .banner-content h1 span { color: var(--accent); }

    .welcome-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-top: 1rem;
      background: rgba(232,197,71,0.1);
      border: 1px solid rgba(232,197,71,0.25);
      padding: 0.35rem 1rem;
      border-radius: 999px;
      font-size: 0.78rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--accent);
    }

    /* ── DASHBOARD GRID ─────────────────────────── */
    .dashboard {
      max-width: 1000px;
      margin: 0 auto;
      padding: 3rem 2rem 5rem;
    }

    .section-label {
      font-size: 0.65rem;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 1.25rem;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1px;
      background: var(--border);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 3rem;
    }

    .card {
      background: var(--surface);
      padding: 1.5rem;
      text-decoration: none;
      color: inherit;
      transition: background 0.2s;
      display: block;
    }

    .card:hover { background: #161616; }

    .card-icon {
      font-size: 1.5rem;
      margin-bottom: 0.75rem;
      display: block;
    }

    .card-title {
      font-family: var(--font-head);
      font-size: 1.2rem;
      letter-spacing: 0.06em;
      color: var(--text);
      margin-bottom: 0.25rem;
    }

    .card-desc {
      font-size: 0.78rem;
      color: var(--muted);
      line-height: 1.5;
    }

    /* Activity feed */
    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 1px;
      background: var(--border);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    .activity-item {
      background: var(--surface);
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      font-size: 0.83rem;
    }

    .activity-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--accent);
      flex-shrink: 0;
    }

    .activity-text { flex: 1; color: #aaa; }
    .activity-text strong { color: var(--text); font-weight: 500; }
    .activity-time { font-size: 0.72rem; color: var(--muted); }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      .dashboard { padding: 2rem 1rem 4rem; }
      .cards { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<!-- Overlay -->
<div class="overlay" id="overlay" onclick="closeMenu()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">TITAN</div>
    <div class="sidebar-tagline">Member Portal</div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Main</div>
    <a href="#" class="active">
      <i class="icon">⊞</i> Dashboard
    </a>
    <a href="#">
      <i class="icon">◎</i> Profile
    </a>
    <a href="#">
      <i class="icon">◈</i> Membership
    </a>

    <div class="sidebar-section-label" style="margin-top:0.5rem;">Shop</div>
    <a href="#">
      <i class="icon">▦</i> Products
    </a>
    <a href="#">
      <i class="icon">◆</i> Current Sales
    </a>
    <a href="#">
      <i class="icon">○</i> My Orders
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="includes/logout.inc.php">
      <i class="icon">⇠</i> Log Out
    </a>
  </div>
</aside>

<!-- Top Nav -->
<header class="topnav">
  <button class="menu-toggle" id="menu-toggle" onclick="toggleMenu()" aria-label="Toggle menu">☰</button>
  <a href="#" class="topnav-logo">TITAN</a>
  <div class="topnav-spacer"></div>
  <div class="topnav-user">
    <div class="topnav-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
    <?= $username ?>
  </div>
</header>

<!-- Hero Banner -->
<section class="banner">
  <div class="banner-content">
    <h1>Welcome <span>Back.</span></h1>
    <div class="welcome-pill">⚡ <?= $username ?> — Member Active</div>
  </div>
</section>

<!-- Dashboard Content -->
<main class="dashboard">

  <div class="section-label">Quick Access</div>
  <div class="cards">
    <a href="#" class="card">
      <span class="card-icon">◎</span>
      <div class="card-title">Profile</div>
      <div class="card-desc">Update your info and preferences</div>
    </a>
    <a href="#" class="card">
      <span class="card-icon">◈</span>
      <div class="card-title">Membership</div>
      <div class="card-desc">View your plan and benefits</div>
    </a>
    <a href="#" class="card">
      <span class="card-icon">▦</span>
      <div class="card-title">Products</div>
      <div class="card-desc">Browse our full gear catalog</div>
    </a>
    <a href="#" class="card">
      <span class="card-icon">◆</span>
      <div class="card-title">Current Sales</div>
      <div class="card-desc">Exclusive deals for members</div>
    </a>
  </div>

  <div class="section-label">Recent Activity</div>
  <div class="activity-list">
    <div class="activity-item">
      <div class="activity-dot"></div>
      <div class="activity-text">You <strong>logged in</strong> to your account</div>
      <div class="activity-time">Just now</div>
    </div>
    <div class="activity-item">
      <div class="activity-dot" style="background:var(--muted)"></div>
      <div class="activity-text"><strong>Membership</strong> is active and up to date</div>
      <div class="activity-time">Today</div>
    </div>
    <div class="activity-item">
      <div class="activity-dot" style="background:var(--muted)"></div>
      <div class="activity-text">New sale event — <strong>Members get 20% off</strong></div>
      <div class="activity-time">2 days ago</div>
    </div>
  </div>

</main>

<script>
  function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('overlay').classList.toggle('active');
  }

  function closeMenu() {
    document.getElementById('sidebar').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
  }

  // Close sidebar on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });
</script>

</body>
</html>