<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header('location: index.php');
    exit();
}

$username = htmlspecialchars($_SESSION['useruid']);
$initial = strtoupper(substr($username, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#080808">
  <title>TITAN | Member Dashboard</title>
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
      --line: rgba(255,255,255,.085);
      --line-strong: rgba(255,255,255,.14);
      --text: #f7f4ec;
      --muted: #96938c;
      --muted-2: #66635d;
      --gold: #e5bf52;
      --gold-2: #b79535;
      --success: #64d58a;
      --danger: #ff6b6b;
      --sidebar: 250px;
      --font-display: 'Bebas Neue', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    body {
      min-height: 100vh;
      background:
        radial-gradient(circle at 85% 5%, rgba(229,191,82,.06), transparent 25%),
        var(--bg);
      color: var(--text);
      font-family: var(--font-body);
      line-height: 1.5;
    }

    button, input { font: inherit; }
    a { color: inherit; }

    .sidebar {
      position: fixed;
      inset: 0 auto 0 0;
      width: var(--sidebar);
      z-index: 60;
      display: flex;
      flex-direction: column;
      background: rgba(14,14,14,.96);
      border-right: 1px solid var(--line);
      backdrop-filter: blur(16px);
      transition: transform .25s ease;
    }

    .sidebar-brand {
      height: 78px;
      padding: 0 1.35rem;
      display: flex;
      align-items: center;
      gap: .7rem;
      border-bottom: 1px solid var(--line);
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
    }

    .brand-copy strong {
      display: block;
      font-family: var(--font-display);
      font-size: 1.65rem;
      font-weight: 400;
      letter-spacing: .14em;
      line-height: 1;
    }

    .brand-copy span {
      display: block;
      margin-top: .18rem;
      color: var(--muted-2);
      font-size: .58rem;
      font-weight: 700;
      letter-spacing: .16em;
      text-transform: uppercase;
    }

    .sidebar-nav { flex: 1; padding: 1rem .75rem; }
    .nav-label {
      padding: .65rem .75rem .4rem;
      color: var(--muted-2);
      font-size: .6rem;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
    }

    .nav-link {
      margin-bottom: .25rem;
      padding: .72rem .75rem;
      display: flex;
      align-items: center;
      gap: .75rem;
      border: 1px solid transparent;
      border-radius: 10px;
      color: #8f8c85;
      text-decoration: none;
      font-size: .79rem;
      font-weight: 600;
      letter-spacing: .03em;
      transition: .2s ease;
    }

    .nav-icon {
      width: 30px;
      height: 30px;
      display: grid;
      place-items: center;
      border-radius: 8px;
      background: #171717;
      color: var(--muted);
      font-size: .78rem;
      transition: .2s ease;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--text);
      background: rgba(229,191,82,.055);
      border-color: rgba(229,191,82,.12);
    }

    .nav-link:hover .nav-icon,
    .nav-link.active .nav-icon {
      background: rgba(229,191,82,.1);
      color: var(--gold);
    }

    .sidebar-user {
      padding: 1rem;
      border-top: 1px solid var(--line);
    }

    .user-card {
      padding: .8rem;
      display: flex;
      align-items: center;
      gap: .75rem;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #0b0b0b;
    }

    .avatar {
      width: 38px;
      height: 38px;
      display: grid;
      place-items: center;
      flex: 0 0 auto;
      border-radius: 50%;
      background: var(--gold);
      color: #090909;
      font-family: var(--font-display);
      font-size: 1.3rem;
    }

    .user-meta { min-width: 0; flex: 1; }
    .user-meta strong {
      display: block;
      overflow: hidden;
      color: var(--text);
      font-size: .77rem;
      font-weight: 600;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .user-meta span { color: var(--muted-2); font-size: .65rem; }

    .logout {
      display: grid;
      place-items: center;
      width: 31px;
      height: 31px;
      border-radius: 8px;
      color: var(--muted);
      text-decoration: none;
      transition: .2s ease;
    }
    .logout:hover { background: rgba(255,107,107,.08); color: var(--danger); }

    .main-shell { margin-left: var(--sidebar); min-height: 100vh; }

    .topbar {
      height: 78px;
      padding: 0 clamp(1rem, 3vw, 2.25rem);
      position: sticky;
      top: 0;
      z-index: 40;
      display: flex;
      align-items: center;
      gap: 1rem;
      border-bottom: 1px solid var(--line);
      background: rgba(8,8,8,.78);
      backdrop-filter: blur(18px);
    }

    .menu-btn {
      display: none;
      width: 38px;
      height: 38px;
      border: 1px solid var(--line);
      border-radius: 10px;
      background: var(--surface);
      color: var(--text);
      cursor: pointer;
    }

    .topbar-title strong {
      display: block;
      font-family: var(--font-display);
      font-size: 1.35rem;
      font-weight: 400;
      letter-spacing: .07em;
      line-height: 1;
    }
    .topbar-title span { color: var(--muted-2); font-size: .65rem; }

    .topbar-spacer { flex: 1; }

    .secure-pill {
      display: inline-flex;
      align-items: center;
      gap: .48rem;
      padding: .48rem .7rem;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: #0d0d0d;
      color: var(--muted);
      font-size: .66rem;
      font-weight: 600;
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    .secure-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--success); }

    .dashboard {
      width: min(1200px, calc(100% - 2rem));
      margin: 0 auto;
      padding: clamp(1rem, 3vw, 2rem) 0 3rem;
    }

    .hero {
      min-height: 330px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: flex-end;
      border: 1px solid var(--line);
      border-radius: 22px;
      background:
        linear-gradient(90deg, rgba(8,8,8,.92) 0%, rgba(8,8,8,.68) 48%, rgba(8,8,8,.22) 100%),
        linear-gradient(0deg, rgba(8,8,8,.85), rgba(8,8,8,.04)),
        url('assets/Titan.jpg') center 35%/cover no-repeat;
      box-shadow: 0 24px 70px rgba(0,0,0,.32);
    }

    .hero::after {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      background:
        linear-gradient(rgba(255,255,255,.026) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.026) 1px, transparent 1px);
      background-size: 48px 48px;
    }

    .hero-content {
      position: relative;
      z-index: 1;
      width: min(650px, 100%);
      padding: clamp(1.5rem, 4vw, 2.7rem);
    }

    .eyebrow {
      color: var(--gold);
      font-size: .65rem;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
    }

    .hero h1 {
      margin-top: .35rem;
      font-family: var(--font-display);
      font-size: clamp(3.8rem, 7vw, 6.5rem);
      font-weight: 400;
      letter-spacing: .03em;
      line-height: .9;
    }

    .hero h1 span { color: var(--gold); }
    .hero p { max-width: 540px; margin-top: .85rem; color: #b4b0a8; font-size: .9rem; }

    .hero-actions { margin-top: 1.3rem; display: flex; flex-wrap: wrap; gap: .65rem; }
    .btn {
      min-height: 42px;
      padding: 0 1rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .45rem;
      border-radius: 10px;
      border: 1px solid var(--line);
      background: rgba(15,15,15,.72);
      color: var(--text);
      text-decoration: none;
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      backdrop-filter: blur(8px);
      transition: .2s ease;
    }
    .btn:hover { transform: translateY(-1px); border-color: var(--line-strong); }
    .btn.primary { background: var(--gold); border-color: var(--gold); color: #090909; }
    .btn.primary:hover { background: #f0ca61; border-color: #f0ca61; }

    .section-head {
      margin: 2rem 0 .9rem;
      display: flex;
      align-items: end;
      justify-content: space-between;
      gap: 1rem;
    }

    .section-head h2 {
      font-family: var(--font-display);
      font-size: 1.7rem;
      font-weight: 400;
      letter-spacing: .05em;
    }
    .section-head p { color: var(--muted-2); font-size: .72rem; }

    .metric-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: .8rem;
    }

    .metric-card,
    .action-card,
    .activity-panel,
    .security-panel {
      border: 1px solid var(--line);
      border-radius: 16px;
      background: var(--surface);
    }

    .metric-card { padding: 1.2rem; }
    .metric-top { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .metric-icon {
      width: 34px;
      height: 34px;
      display: grid;
      place-items: center;
      border-radius: 9px;
      background: rgba(229,191,82,.085);
      color: var(--gold);
      font-size: .8rem;
    }
    .metric-status { color: var(--success); font-size: .62rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .metric-value { margin-top: 1rem; font-family: var(--font-display); font-size: 2rem; line-height: 1; letter-spacing: .04em; }
    .metric-label { margin-top: .25rem; color: var(--muted); font-size: .7rem; }

    .action-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: .8rem;
    }

    .action-card {
      min-height: 160px;
      padding: 1.15rem;
      display: flex;
      flex-direction: column;
      text-decoration: none;
      transition: transform .2s, border-color .2s, background .2s;
    }
    .action-card:hover { transform: translateY(-2px); border-color: rgba(229,191,82,.26); background: var(--surface-2); }
    .action-icon {
      width: 39px;
      height: 39px;
      display: grid;
      place-items: center;
      border: 1px solid var(--line);
      border-radius: 10px;
      background: #0c0c0c;
      color: var(--gold);
      font-size: .85rem;
    }
    .action-card h3 { margin-top: auto; font-family: var(--font-display); font-size: 1.25rem; font-weight: 400; letter-spacing: .05em; }
    .action-card p { margin-top: .15rem; color: var(--muted); font-size: .69rem; }

    .lower-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.45fr) minmax(280px, .55fr);
      gap: .8rem;
    }

    .panel-head {
      padding: 1rem 1.15rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--line);
    }
    .panel-head h3 { font-size: .78rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; }
    .panel-head span { color: var(--muted-2); font-size: .65rem; }

    .activity-item {
      min-height: 68px;
      padding: .85rem 1.15rem;
      display: flex;
      align-items: center;
      gap: .85rem;
      border-bottom: 1px solid var(--line);
    }
    .activity-item:last-child { border-bottom: 0; }
    .activity-dot { width: 8px; height: 8px; flex: 0 0 auto; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 5px rgba(229,191,82,.06); }
    .activity-copy { min-width: 0; flex: 1; }
    .activity-copy strong { display: block; font-size: .76rem; font-weight: 600; }
    .activity-copy span { color: var(--muted); font-size: .68rem; }
    .activity-time { color: var(--muted-2); font-size: .63rem; white-space: nowrap; }

    .security-body { padding: 1.1rem; }
    .security-badge {
      width: 48px;
      height: 48px;
      display: grid;
      place-items: center;
      border: 1px solid rgba(100,213,138,.18);
      border-radius: 14px;
      background: rgba(100,213,138,.07);
      color: var(--success);
      font-size: 1rem;
    }
    .security-body h4 { margin-top: .9rem; font-family: var(--font-display); font-size: 1.35rem; font-weight: 400; letter-spacing: .04em; }
    .security-body p { margin-top: .35rem; color: var(--muted); font-size: .7rem; line-height: 1.6; }
    .security-list { margin-top: 1rem; display: grid; gap: .55rem; }
    .security-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; font-size: .67rem; }
    .security-row span:first-child { color: var(--muted); }
    .security-row span:last-child { color: #c9c6bf; font-weight: 600; }

    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 55;
      background: rgba(0,0,0,.68);
      backdrop-filter: blur(4px);
    }

    @media (max-width: 1050px) {
      .action-grid { grid-template-columns: repeat(2, 1fr); }
      .lower-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 820px) {
      .sidebar { transform: translateX(-100%); box-shadow: 20px 0 60px rgba(0,0,0,.45); }
      .sidebar.open { transform: translateX(0); }
      .overlay.open { display: block; }
      .main-shell { margin-left: 0; }
      .menu-btn { display: grid; place-items: center; }
      .metric-grid { grid-template-columns: 1fr; }
      .hero { min-height: 360px; }
    }

    @media (max-width: 560px) {
      .topbar { height: 66px; }
      .secure-pill { display: none; }
      .dashboard { width: calc(100% - 1rem); padding-top: .5rem; }
      .hero { min-height: 380px; border-radius: 17px; background-position: 58% center; }
      .hero-content { padding: 1.35rem; }
      .action-grid { grid-template-columns: 1fr; }
      .action-card { min-height: 135px; }
      .section-head { margin-top: 1.5rem; }
      .activity-item { align-items: flex-start; }
      .activity-time { margin-left: auto; }
    }
  </style>
</head>
<body>
  <div class="overlay" id="overlay"></div>

  <aside class="sidebar" id="sidebar">
    <a class="sidebar-brand" href="home.php">
      <span class="brand-mark">T</span>
      <span class="brand-copy"><strong>TITAN</strong><span>Member Portal</span></span>
    </a>

    <nav class="sidebar-nav" aria-label="Member navigation">
      <div class="nav-label">Overview</div>
      <a class="nav-link active" href="home.php"><span class="nav-icon">⌂</span> Dashboard</a>
      <a class="nav-link" href="#"><span class="nav-icon">◎</span> Profile</a>
      <a class="nav-link" href="#"><span class="nav-icon">◇</span> Membership</a>

      <div class="nav-label" style="margin-top:.8rem;">Store</div>
      <a class="nav-link" href="#"><span class="nav-icon">▦</span> Products</a>
      <a class="nav-link" href="#"><span class="nav-icon">◆</span> Current Sales</a>
      <a class="nav-link" href="#"><span class="nav-icon">≡</span> My Orders</a>
    </nav>

    <div class="sidebar-user">
      <div class="user-card">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <div class="user-meta">
          <strong><?= $username ?></strong>
          <span>TITAN Member</span>
        </div>
        <a class="logout" href="includes/logout.inc.php" title="Log out" aria-label="Log out">↗</a>
      </div>
    </div>
  </aside>

  <div class="main-shell">
    <header class="topbar">
      <button class="menu-btn" id="menu-btn" type="button" aria-label="Open navigation">☰</button>
      <div class="topbar-title"><strong>Dashboard</strong><span>Your TITAN account at a glance</span></div>
      <div class="topbar-spacer"></div>
      <div class="secure-pill"><span class="secure-dot"></span> Session active</div>
    </header>

    <main class="dashboard">
      <section class="hero">
        <div class="hero-content">
          <div class="eyebrow">Welcome back, <?= $username ?></div>
          <h1>Stay <span>Relentless.</span></h1>
          <p>Your account is active. Manage your profile, membership and member-only access from one place.</p>
          <div class="hero-actions">
            <a class="btn primary" href="#">View Membership</a>
            <a class="btn" href="#">Edit Profile</a>
          </div>
        </div>
      </section>

      <div class="section-head">
        <div><h2>Account Overview</h2><p>Current member status</p></div>
      </div>

      <section class="metric-grid" aria-label="Account metrics">
        <article class="metric-card">
          <div class="metric-top"><div class="metric-icon">✓</div><div class="metric-status">Active</div></div>
          <div class="metric-value">Member</div>
          <div class="metric-label">Your account is ready to use</div>
        </article>
        <article class="metric-card">
          <div class="metric-top"><div class="metric-icon">⌁</div><div class="metric-status">Secure</div></div>
          <div class="metric-value">Protected</div>
          <div class="metric-label">Authenticated PHP session</div>
        </article>
        <article class="metric-card">
          <div class="metric-top"><div class="metric-icon">T+</div><div class="metric-status">Ready</div></div>
          <div class="metric-value">Benefits</div>
          <div class="metric-label">Member access and promotions</div>
        </article>
      </section>

      <div class="section-head">
        <div><h2>Quick Access</h2><p>Jump back into your account</p></div>
      </div>

      <section class="action-grid" aria-label="Quick actions">
        <a class="action-card" href="#">
          <span class="action-icon">◎</span>
          <h3>Profile</h3>
          <p>Update account information</p>
        </a>
        <a class="action-card" href="#">
          <span class="action-icon">◇</span>
          <h3>Membership</h3>
          <p>Review plan and benefits</p>
        </a>
        <a class="action-card" href="#">
          <span class="action-icon">▦</span>
          <h3>Products</h3>
          <p>Browse the TITAN catalog</p>
        </a>
        <a class="action-card" href="#">
          <span class="action-icon">◆</span>
          <h3>Member Sales</h3>
          <p>See current exclusive offers</p>
        </a>
      </section>

      <div class="section-head">
        <div><h2>Account Activity</h2><p>Recent access and security</p></div>
      </div>

      <section class="lower-grid">
        <div class="activity-panel">
          <div class="panel-head"><h3>Recent Activity</h3><span>Latest events</span></div>
          <div class="activity-item">
            <span class="activity-dot"></span>
            <div class="activity-copy"><strong>Successful sign in</strong><span>Your TITAN member session started successfully.</span></div>
            <span class="activity-time">Just now</span>
          </div>
          <div class="activity-item">
            <span class="activity-dot" style="background:#66635d;box-shadow:none;"></span>
            <div class="activity-copy"><strong>Account status verified</strong><span>Your member account is active.</span></div>
            <span class="activity-time">Today</span>
          </div>
          <div class="activity-item">
            <span class="activity-dot" style="background:#66635d;box-shadow:none;"></span>
            <div class="activity-copy"><strong>Member portal available</strong><span>Your protected dashboard is ready.</span></div>
            <span class="activity-time">Today</span>
          </div>
        </div>

        <aside class="security-panel">
          <div class="panel-head"><h3>Security</h3><span>Account health</span></div>
          <div class="security-body">
            <div class="security-badge">✓</div>
            <h4>Session Protected</h4>
            <p>This page checks for an authenticated user session before loading protected member content.</p>
            <div class="security-list">
              <div class="security-row"><span>Authentication</span><span>Active</span></div>
              <div class="security-row"><span>Member</span><span><?= $username ?></span></div>
              <div class="security-row"><span>Access</span><span>Protected</span></div>
            </div>
          </div>
        </aside>
      </section>
    </main>
  </div>

  <script>
    const menuButton = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function openMenu() {
      sidebar.classList.add('open');
      overlay.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
      sidebar.classList.remove('open');
      overlay.classList.remove('open');
      document.body.style.overflow = '';
    }

    menuButton.addEventListener('click', openMenu);
    overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape') closeMenu();
    });
  </script>
</body>
</html>
