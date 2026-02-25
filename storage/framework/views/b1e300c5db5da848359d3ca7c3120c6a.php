<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ZENITH — Built for Businesses That Move Fast</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="<?php echo e(asset('logo.png')); ?>" rel="icon" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <meta name="description" content="Zenith is a powerful inventory management system designed to streamline your operations, enhance visibility, and optimize stock control. Start your journey with Zenith and elevate your inventory management for free!"/>
<meta name="canonical" content="https://zenith.rs/demo/sign-in.html">
<meta name="twitter:image:src" content="https://zenith.rs/static/og.png">
<meta name="twitter:site" content="@zenith_pro">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Zenith: Premium Inventory Management System with Responsive and High-Quality UI.">
<meta name="twitter:description" content="Zenith is a comprehensive inventory management solution that comes with a range of features to help you manage your stock efficiently. Start your adventure with Zenith and transform your inventory management for free!">
<meta property="og:description" content="Zenith is a powerful inventory management system designed to streamline your operations, enhance visibility, and optimize stock control. Start your journey with Zenith and elevate your inventory management for free!">
<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --cobalt: #1a56db;
    --cobalt-dark: #1040b0;
    --cobalt-light: #3b82f6;
    --cobalt-ultra: #eff6ff;
    --cobalt-mid: #bfdbfe;
    --ink: #0a0f1e;
    --ink-mid: #1e2a3a;
    --ink-light: #4a5568;
    --ink-faint: #718096;
    --white: #ffffff;
    --off-white: #f8faff;
    --border: #e2eaf7;
    --border-strong: #c7d9f5;
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--white);
    color: var(--ink);
    overflow-x: hidden;
  }

  h1, h2, h3, h4, h5, .nav-logo { font-family: 'Syne', sans-serif; }

  /* ── NOISE TEXTURE OVERLAY ── */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 9999;
    opacity: 0.4;
  }

  /* ── NAV ── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 1000;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 0 5%;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: box-shadow 0.3s;
  }
  nav.scrolled { box-shadow: 0 4px 32px rgba(26,86,219,0.08); }

  .nav-logo {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--ink);
    letter-spacing: -0.04em;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .nav-logo .logo-mark {
    width: 32px; height: 32px;
    background: var(--cobalt);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-size: 0.85rem;
    font-weight: 800;
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
    list-style: none;
  }
  .nav-links a {
    text-decoration: none;
    color: var(--ink-light);
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s;
    font-family: 'DM Sans', sans-serif;
  }
  .nav-links a:hover { color: var(--cobalt); }

  .nav-cta {
    display: flex; gap: 10px; align-items: center;
  }
  .btn-ghost {
    padding: 9px 20px;
    border: 1.5px solid var(--border-strong);
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--ink);
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s;
    background: transparent;
    cursor: pointer;
  }
  .btn-ghost:hover { border-color: var(--cobalt); color: var(--cobalt); background: var(--cobalt-ultra); }

  .btn-primary {
    padding: 9px 22px;
    background: var(--cobalt);
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-primary:hover { background: var(--cobalt-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,86,219,0.3); }

  .btn-primary-lg {
    padding: 15px 32px;
    font-size: 1rem;
    border-radius: 10px;
  }
  .btn-secondary-lg {
    padding: 14px 30px;
    font-size: 1rem;
    border-radius: 10px;
    border: 2px solid var(--border-strong);
    color: var(--ink);
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 8px;
    background: white;
  }
  .btn-secondary-lg:hover { border-color: var(--cobalt); color: var(--cobalt); transform: translateY(-1px); }

  /* ── HERO ── */
  #hero {
    min-height: 100vh;
    padding-top: 68px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    background: var(--white);
  }

  /* Dot grid */
  #hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, #c7d9f5 1px, transparent 1px);
    background-size: 28px 28px;
    opacity: 0.5;
  }

  /* Blue glow blob */
  .hero-blob {
    position: absolute;
    width: 700px; height: 700px;
    background: radial-gradient(ellipse, rgba(26,86,219,0.08) 0%, transparent 70%);
    top: -100px; right: -100px;
    pointer-events: none;
  }
  .hero-blob-2 {
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(ellipse, rgba(59,130,246,0.06) 0%, transparent 70%);
    bottom: -50px; left: -100px;
    pointer-events: none;
  }

  .hero-inner {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 5%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--cobalt-ultra);
    border: 1px solid var(--cobalt-mid);
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--cobalt);
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 24px;
  }
  .hero-badge .dot { width: 6px; height: 6px; background: var(--cobalt); border-radius: 50%; animation: pulse 2s infinite; }

  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
  }

  .hero-title {
    font-size: clamp(2.8rem, 5vw, 4.2rem);
    font-weight: 800;
    line-height: 1.08;
    letter-spacing: -0.04em;
    color: var(--ink);
    margin-bottom: 20px;
  }
  .hero-title .highlight {
    color: var(--cobalt);
    position: relative;
  }
  .hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: 4px; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--cobalt), var(--cobalt-light));
    border-radius: 2px;
    opacity: 0.3;
  }

  .hero-sub {
    font-size: 1.1rem;
    color: var(--ink-faint);
    line-height: 1.7;
    margin-bottom: 36px;
    max-width: 480px;
    font-weight: 300;
  }

  .hero-actions { display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }

  .hero-trust {
    margin-top: 48px;
    padding-top: 32px;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 32px;
  }
  .trust-stat { }
  .trust-num {
    font-family: 'Syne', sans-serif;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--ink);
    line-height: 1;
  }
  .trust-label {
    font-size: 0.8rem;
    color: var(--ink-faint);
    margin-top: 4px;
    font-weight: 400;
  }

  /* Dashboard mockup */
  .hero-visual {
    position: relative;
  }
  .dashboard-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 8px 48px rgba(26,86,219,0.1), 0 2px 8px rgba(0,0,0,0.04);
    padding: 24px;
    animation: floatUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
  }
  .dashboard-card.delay-1 { animation-delay: 0.2s; }
  .dashboard-card.delay-2 { animation-delay: 0.4s; }

  @keyframes floatUp {
    to { opacity: 1; transform: translateY(0); }
  }

  .db-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px;
  }
  .db-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; color: var(--ink); }
  .db-badge { background: #dcfce7; color: #166534; font-size: 0.72rem; font-weight: 600; padding: 4px 10px; border-radius: 100px; }
  .db-badge.warn { background: #fef3c7; color: #92400e; }

  .db-stats {
    display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px;
  }
  .db-stat-box {
    background: var(--off-white);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px;
  }
  .db-stat-val { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 800; color: var(--ink); }
  .db-stat-lbl { font-size: 0.7rem; color: var(--ink-faint); margin-top: 2px; }
  .db-stat-box.active { background: var(--cobalt); border-color: var(--cobalt); }
  .db-stat-box.active .db-stat-val { color: white; }
  .db-stat-box.active .db-stat-lbl { color: rgba(255,255,255,0.7); }

  /* mini bar chart */
  .mini-chart { display: flex; align-items: flex-end; gap: 5px; height: 50px; }
  .bar { background: var(--cobalt-mid); border-radius: 3px 3px 0 0; flex: 1; transition: background 0.3s; }
  .bar.active { background: var(--cobalt); }
  .bar:hover { background: var(--cobalt-dark); }

  /* inventory list */
  .inv-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.82rem;
  }
  .inv-row:last-child { border-bottom: none; }
  .inv-name { font-weight: 500; color: var(--ink); }
  .inv-sku { color: var(--ink-faint); font-size: 0.75rem; margin-top: 2px; }
  .inv-qty { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--ink); }
  .inv-status { font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 100px; }
  .status-ok { background: #dcfce7; color: #166534; }
  .status-low { background: #fef3c7; color: #92400e; }
  .status-out { background: #fee2e2; color: #991b1b; }

  .float-card {
    position: absolute;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 12px 40px rgba(26,86,219,0.12);
    padding: 14px 18px;
    animation: floatUp 0.8s ease 0.6s forwards;
    opacity: 0;
    transform: translateY(20px);
  }
  .float-card.card-a { bottom: -20px; left: -40px; }
  .float-card.card-b { top: -20px; right: -20px; }
  .float-card-icon { font-size: 1.2rem; margin-bottom: 4px; }
  .float-card-val { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 800; color: var(--ink); }
  .float-card-lbl { font-size: 0.7rem; color: var(--ink-faint); }

  /* ── SECTIONS ── */
  section { padding: 100px 5%; }
  .container { max-width: 1200px; margin: 0 auto; }

  .section-tag {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--cobalt);
    background: var(--cobalt-ultra);
    border: 1px solid var(--cobalt-mid);
    padding: 5px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
  }
  .section-title {
    font-size: clamp(2rem, 3.5vw, 2.8rem);
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--ink);
    line-height: 1.15;
    margin-bottom: 16px;
  }
  .section-sub {
    font-size: 1rem;
    color: var(--ink-faint);
    line-height: 1.7;
    max-width: 520px;
    font-weight: 300;
  }

  /* ── FEATURES ── */
  #features { background: var(--off-white); }
  .features-head { text-align: center; margin-bottom: 64px; }
  .features-head .section-sub { margin: 0 auto; }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .feature-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px 28px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }
  .feature-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--cobalt), var(--cobalt-light));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s;
  }
  .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(26,86,219,0.1); border-color: var(--cobalt-mid); }
  .feature-card:hover::before { transform: scaleX(1); }

  .feature-icon {
    width: 48px; height: 48px;
    background: var(--cobalt-ultra);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 20px;
    font-size: 1.3rem;
    border: 1px solid var(--cobalt-mid);
    transition: all 0.3s;
  }
  .feature-card:hover .feature-icon { background: var(--cobalt); }

  .feature-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: 10px; letter-spacing: -0.02em; }
  .feature-desc { font-size: 0.875rem; color: var(--ink-faint); line-height: 1.65; font-weight: 300; }

  /* ── MODULES ── */
  #modules { background: white; }
  .modules-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }
  .modules-list { display: flex; flex-direction: column; gap: 12px; margin-top: 32px; }

  .module-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    border: 1px solid var(--border);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.25s;
    background: white;
  }
  .module-item.active, .module-item:hover {
    background: var(--cobalt-ultra);
    border-color: var(--cobalt-mid);
    box-shadow: 0 4px 20px rgba(26,86,219,0.08);
  }
  .module-item.active .module-num { background: var(--cobalt); color: white; }

  .module-num {
    width: 36px; height: 36px; min-width: 36px;
    background: var(--off-white);
    border: 1px solid var(--border);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 0.85rem;
    color: var(--ink);
    transition: all 0.25s;
  }
  .module-content { }
  .module-name { font-weight: 700; font-size: 0.95rem; color: var(--ink); margin-bottom: 4px; }
  .module-desc { font-size: 0.82rem; color: var(--ink-faint); line-height: 1.5; font-weight: 300; }

  .modules-visual {
    background: var(--off-white);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px;
    min-height: 480px;
    display: flex; flex-direction: column; justify-content: center;
  }

  /* ── PRICING ── */
  #pricing { background: var(--ink); }
  #pricing .section-title { color: white; }
  #pricing .section-tag { background: rgba(255,255,255,0.1); color: var(--cobalt-mid); border-color: rgba(255,255,255,0.15); }
  .pricing-head { text-align: center; margin-bottom: 60px; }
  .pricing-head .section-sub { color: rgba(255,255,255,0.5); margin: 0 auto; }

  .pricing-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    max-width: 960px;
    margin: 0 auto;
  }

  .price-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 36px 28px;
    transition: all 0.3s;
    position: relative;
  }
  .price-card.popular {
    background: var(--cobalt);
    border-color: var(--cobalt-light);
    transform: scale(1.03);
  }
  .price-card:not(.popular):hover { background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.2); }

  .popular-badge {
    position: absolute;
    top: -12px; left: 50%; transform: translateX(-50%);
    background: #f59e0b;
    color: #1a0a00;
    font-size: 0.7rem; font-weight: 700;
    padding: 4px 14px; border-radius: 100px;
    letter-spacing: 0.05em; text-transform: uppercase;
    white-space: nowrap;
  }

  .plan-name { font-family: 'Syne', sans-serif; font-weight: 700; color: rgba(255,255,255,0.6); font-size: 0.8rem; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 12px; }
  .price-card.popular .plan-name { color: rgba(255,255,255,0.8); }

  .plan-price {
    display: flex; align-items: baseline; gap: 4px;
    margin-bottom: 8px;
  }
  .price-currency { font-size: 1.2rem; font-weight: 600; color: white; margin-top: 6px; }
  .price-amount { font-family: 'Syne', sans-serif; font-size: 3rem; font-weight: 800; color: white; line-height: 1; }
  .price-period { font-size: 0.82rem; color: rgba(255,255,255,0.4); }

  .plan-desc { font-size: 0.85rem; color: rgba(255,255,255,0.4); margin-bottom: 28px; line-height: 1.5; }
  .price-card.popular .plan-desc { color: rgba(255,255,255,0.7); }

  .plan-features { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
  .plan-features li { display: flex; align-items: center; gap: 10px; font-size: 0.875rem; color: rgba(255,255,255,0.6); }
  .price-card.popular .plan-features li { color: rgba(255,255,255,0.9); }
  .plan-features li::before { content: '✓'; color: var(--cobalt-light); font-weight: 700; min-width: 16px; }
  .price-card.popular .plan-features li::before { color: white; }

  .btn-plan {
    width: 100%;
    padding: 13px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    text-align: center;
    display: block;
    text-decoration: none;
  }
  .btn-plan-outline {
    background: transparent;
    border: 1.5px solid rgba(255,255,255,0.2);
    color: white;
  }
  .btn-plan-outline:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.4); }
  .btn-plan-white {
    background: white;
    color: var(--cobalt-dark);
  }
  .btn-plan-white:hover { background: var(--cobalt-ultra); transform: translateY(-1px); }

  /* ── TESTIMONIALS ── */
  #testimonials { background: var(--off-white); }
  .testi-head { text-align: center; margin-bottom: 60px; }
  .testi-head .section-sub { margin: 0 auto; }

  .testi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .testi-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px 28px;
    transition: all 0.3s;
    position: relative;
  }
  .testi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(26,86,219,0.08); }

  .testi-stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 16px; letter-spacing: 2px; }
  .testi-quote { font-size: 0.92rem; color: var(--ink-mid); line-height: 1.7; margin-bottom: 24px; font-style: italic; font-weight: 300; }
  .testi-quote::before { content: '"'; font-family: 'Syne', sans-serif; font-size: 3rem; color: var(--cobalt-mid); line-height: 0; position: relative; top: 12px; margin-right: 4px; }

  .testi-author { display: flex; align-items: center; gap: 12px; }
  .testi-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 0.9rem;
    color: white;
    background: var(--cobalt);
    flex-shrink: 0;
  }
  .testi-name { font-weight: 700; font-size: 0.88rem; color: var(--ink); }
  .testi-role { font-size: 0.77rem; color: var(--ink-faint); margin-top: 2px; }

  /* ── CONTACT ── */
  #contact { background: white; }
  .contact-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: start;
  }

  .contact-info { }
  .contact-info .section-title { margin-bottom: 16px; }
  .contact-info .section-sub { margin-bottom: 40px; }

  .contact-detail {
    display: flex; gap: 14px; align-items: flex-start;
    margin-bottom: 24px;
  }
  .contact-detail-icon {
    width: 42px; height: 42px; min-width: 42px;
    background: var(--cobalt-ultra);
    border: 1px solid var(--cobalt-mid);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
  }
  .contact-detail-label { font-size: 0.75rem; color: var(--ink-faint); font-weight: 500; margin-bottom: 2px; }
  .contact-detail-val { font-size: 0.95rem; font-weight: 600; color: var(--ink); }

  .contact-form-wrap {
    background: var(--off-white);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px 36px;
  }

  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
  .form-label { font-size: 0.8rem; font-weight: 600; color: var(--ink); }
  .form-input, .form-select, .form-textarea {
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    color: var(--ink);
    background: white;
    transition: all 0.2s;
    outline: none;
    width: 100%;
  }
  .form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--cobalt);
    box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
  }
  .form-input::placeholder, .form-textarea::placeholder { color: var(--ink-faint); }
  .form-textarea { resize: vertical; min-height: 110px; }

  .submit-btn {
    width: 100%;
    padding: 15px;
    background: var(--cobalt);
    color: white;
    border: none;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 4px;
  }
  .submit-btn:hover { background: var(--cobalt-dark); transform: translateY(-1px); box-shadow: 0 6px 24px rgba(26,86,219,0.28); }

  /* ── FOOTER ── */
  footer {
    background: var(--ink);
    padding: 60px 5% 32px;
    color: rgba(255,255,255,0.4);
  }
  .footer-top {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 48px;
    margin-bottom: 48px;
    padding-bottom: 48px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }
  .footer-brand .nav-logo { color: white; margin-bottom: 16px; display: inline-flex; }
  .footer-brand p { font-size: 0.85rem; line-height: 1.65; color: rgba(255,255,255,0.4); font-weight: 300; }

  .footer-col-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem; color: white; margin-bottom: 16px; }
  .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
  .footer-links a { font-size: 0.83rem; color: rgba(255,255,255,0.4); text-decoration: none; transition: color 0.2s; }
  .footer-links a:hover { color: white; }

  .footer-bottom { display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; }
  .footer-bottom a { color: rgba(255,255,255,0.4); text-decoration: none; }
  .footer-bottom a:hover { color: white; }

  /* ── SCROLL ANIMATIONS ── */
  .fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.7s ease, transform 0.7s ease;
  }
  .fade-in.visible { opacity: 1; transform: translateY(0); }
  .fade-in.delay-1 { transition-delay: 0.1s; }
  .fade-in.delay-2 { transition-delay: 0.2s; }
  .fade-in.delay-3 { transition-delay: 0.3s; }

  /* ── DIVIDER ── */
  .divider {
    width: 100%; height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-strong), transparent);
    margin: 0;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 768px) {
    .hero-inner { grid-template-columns: 1fr; gap: 48px; }
    .hero-visual { display: none; }
    .features-grid, .testi-grid, .pricing-grid { grid-template-columns: 1fr; }
    .modules-layout { grid-template-columns: 1fr; }
    .modules-visual { display: none; }
    .contact-layout { grid-template-columns: 1fr; }
    .footer-top { grid-template-columns: 1fr 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .nav-links { display: none; }
    .price-card.popular { transform: none; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav id="navbar">
  <a href="#" class="nav-logo">
    <div class="logo-mark">Z</div>
    ZENITH
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#modules">Modules</a></li>
    <li><a href="#pricing">Pricing</a></li>
    <li><a href="#testimonials">Testimonials</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>
  <div class="nav-cta">
    <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Sign in</a>
    <a href="#contact" class="btn-primary">Get Demo →</a>
  </div>
</nav>

<!-- HERO -->
<section id="hero">
  <div class="hero-blob"></div>
  <div class="hero-blob-2"></div>
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-badge"><span class="dot"></span> Inventory Management Platform</div>
      <h1 class="hero-title">Built for Businesses<br>That <span class="highlight">Move Fast</span></h1>
      <p class="hero-sub">ZENITH gives your team real-time visibility into every  order, and supplier — so decisions happen in seconds, not days.</p>
      <div class="hero-actions">
        <a href="#contact" class="btn-primary btn-primary-lg">Start Free Trial →</a>
        <a href="#modules" class="btn-secondary-lg">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M6.5 5.5l4 2.5-4 2.5V5.5z" fill="currentColor"/></svg>
          See How It Works
        </a>
      </div>
      <div class="hero-trust">
        <div class="trust-stat"><div class="trust-num">120+</div><div class="trust-label">Businesses Worldwide</div></div>
        <div class="trust-stat"><div class="trust-num">99.9%</div><div class="trust-label">Uptime SLA</div></div>
        <div class="trust-stat"><div class="trust-num">2+</div><div class="trust-label">Countries</div></div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="float-card card-b">
        <div class="float-card-icon">📦</div>
        <div class="float-card-val">+12.4%</div>
        <div class="float-card-lbl">Stock Turnover</div>
      </div>

      <div class="dashboard-card delay-1">
        <div class="db-header">
          <div class="db-title">Inventory Overview</div>
          <div class="db-badge">Live ●</div>
        </div>
        <div class="db-stats">
          <div class="db-stat-box active">
            <div class="db-stat-val">8,420</div>
            <div class="db-stat-lbl">Total Products</div>
          </div>
          <div class="db-stat-box">
            <div class="db-stat-val">342</div>
            <div class="db-stat-lbl">Pending Orders</div>
          </div>
          <div class="db-stat-box">
            <div class="db-stat-val">94%</div>
            <div class="db-stat-lbl">Fill Rate</div>
          </div>
        </div>
        <div class="mini-chart">
          <div class="bar" style="height: 35%"></div>
          <div class="bar" style="height: 55%"></div>
          <div class="bar" style="height: 42%"></div>
          <div class="bar" style="height: 68%"></div>
          <div class="bar" style="height: 80%"></div>
          <div class="bar active" style="height: 92%"></div>
          <div class="bar" style="height: 75%"></div>
        </div>
      </div>

      <div style="height: 16px;"></div>

      <div class="dashboard-card delay-2">
        <div class="db-header">
          <div class="db-title">Stock Alerts</div>
          <div class="db-badge warn">3 Low Stock</div>
        </div>
        <div class="inv-row">
          <div><div class="inv-name">Wireless Keyboard K3</div><div class="inv-sku">ID-00412</div></div>
          <div style="text-align:right"><div class="inv-qty">1,240</div><div class="inv-status status-ok">In Stock</div></div>
        </div>
        <div class="inv-row">
          <div><div class="inv-name">USB-C Hub Pro</div><div class="inv-sku">ID-00187</div></div>
          <div style="text-align:right"><div class="inv-qty">48</div><div class="inv-status status-low">Low</div></div>
        </div>
        <div class="inv-row">
          <div><div class="inv-name">Monitor Stand XL</div><div class="inv-sku">ID-00553</div></div>
          <div style="text-align:right"><div class="inv-qty">0</div><div class="inv-status status-out">Out</div></div>
        </div>
      </div>

      <div class="float-card card-a">
        <div class="float-card-icon">🚚</div>
        <div class="float-card-val">24 Orders</div>
        <div class="float-card-lbl">Dispatched Today</div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section id="features">
  <div class="container">
    <div class="features-head">
      <div class="section-tag">Core Features</div>
      <h2 class="section-title">Everything Your Team Needs<br>to Stay Ahead</h2>
      <p class="section-sub">From real-time tracking to predictive analytics — ZENITH is the operating system for your inventory.</p>
    </div>
    <div class="features-grid">
      <div class="feature-card fade-in">
        <div class="feature-icon">📊</div>
        <h3 class="feature-title">Real-Time Stock Tracking</h3>
        <p class="feature-desc">Monitor inventory levels across all locations instantly. Automated alerts prevent stockouts before they happen, keeping your operations running without disruption.</p>
      </div>
      <div class="feature-card fade-in delay-1">
        <div class="feature-icon">🔄</div>
        <h3 class="feature-title">Order Lifecycle Management</h3>
        <p class="feature-desc">Track every order from purchase to delivery with full visibility. Streamlined workflows eliminate manual entry errors and accelerate fulfillment cycles.</p>
      </div>
      <div class="feature-card fade-in delay-2">
        <div class="feature-icon">🤝</div>
        <h3 class="feature-title">Supplier Management</h3>
        <p class="feature-desc">Centralize all supplier relationships, contracts, and performance data. Score and compare vendors to make smarter procurement decisions.</p>
      </div>
      <div class="feature-card fade-in">
        <div class="feature-icon">📈</div>
        <h3 class="feature-title">Analytics & Forecasting</h3>
        <p class="feature-desc">Demand forecasting powered by historical data and market trends. Know what to stock, when to reorder, and where to optimize — before issues arise.</p>
      </div>
      <div class="feature-card fade-in delay-1">
        <div class="feature-icon">🔗</div>
        <h3 class="feature-title">Seamless Integrations</h3>
        <p class="feature-desc">Connect to your ERP, e-commerce, accounting, and logistics platforms with 50+ pre-built integrations. Your data flows exactly where you need it.</p>
      </div>
      <div class="feature-card fade-in delay-2">
        <div class="feature-icon">🛡️</div>
        <h3 class="feature-title">Enterprise-Grade Security</h3>
        <p class="feature-desc">Bank-level encryption, role-based access controls, full audit logs, and GDPR compliance. Your data stays private, protected, and yours alone.</p>
      </div>
    </div>
  </div>
</section>

<!-- MODULES -->
<section id="modules">
  <div class="container">
    <div class="modules-layout">
      <div>
        <div class="section-tag">Modules</div>
        <h2 class="section-title">One Platform,<br>Every Operation</h2>
        <p class="section-sub">Purpose-built modules that work together seamlessly — no plugins, no patchwork.</p>
        <div class="modules-list">
          <div class="module-item active" onclick="setActiveModule(this, 0)">
            <div class="module-num">01</div>
            <div class="module-content">
              <div class="module-name">Inventory Control</div>
              <div class="module-desc">Multi-location stock management with real-time sync and batch tracking.</div>
            </div>
          </div>
          <div class="module-item" onclick="setActiveModule(this, 1)">
            <div class="module-num">02</div>
            <div class="module-content">
              <div class="module-name">Order Management</div>
              <div class="module-desc">End-to-end order processing with automated fulfillment and returns.</div>
            </div>
          </div>
          <div class="module-item" onclick="setActiveModule(this, 2)">
            <div class="module-num">03</div>
            <div class="module-content">
              <div class="module-name">Financial Reporting</div>
              <div class="module-desc">P&L by product, category, and location with exportable dashboards.</div>
            </div>
          </div>
          <div class="module-item" onclick="setActiveModule(this, 3)">
            <div class="module-num">04</div>
            <div class="module-content">
              <div class="module-name">Customer Portal</div>
              <div class="module-desc">Self-service portal for B2B customers to place orders and track deliveries.</div>
            </div>
          </div>
        </div>
      </div>
      <div class="modules-visual" id="modules-visual">
        <div id="module-display">
          <div style="margin-bottom: 20px;">
            <div class="section-tag" id="mod-tag">Administration</div>
          </div>
          <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--ink); margin-bottom: 12px; letter-spacing:-0.02em;" id="mod-title">Inventory Control System</h3>
          <p style="font-size: 0.9rem; color: var(--ink-faint); line-height: 1.7; font-weight: 300; margin-bottom: 28px;" id="mod-desc">Gain full visibility across all stock locations. Set reorder points, track expiry dates, and manage bundles — all in one place. ZENITH's inventory control module handles the complexity so your team can focus on growth.</p>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;" id="mod-stats">
            <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
              <div style="font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--cobalt);">98%</div>
              <div style="font-size: 0.75rem; color: var(--ink-faint); margin-top: 4px;">Inventory Accuracy</div>
            </div>
            <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
              <div style="font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--cobalt);">4h</div>
              <div style="font-size: 0.75rem; color: var(--ink-faint); margin-top: 4px;">Avg. Onboarding</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section id="pricing">
    <div class="container">
        <div class="pricing-head">
            <div class="section-tag">Pricing</div>
            <h2 class="section-title">Transparent Pricing.<br>No Surprises.</h2>
            <p class="section-sub" style="color: rgba(255,255,255,0.5);">All plans include a 14-day free trial. No credit card required to get started.</p>
        </div>
        <div class="pricing-grid" style="max-width: 500px;">

            <div class="price-card popular fade-in" style="grid-column: 1 / -1;">
                <div class="popular-badge">Exclusive Offer</div>
                <div class="plan-name">Complete Package</div>
                <div class="plan-price">
                    <span class="price-currency">Tsh</span>
                    <span class="price-amount">500,000</span>
                    <span class="price-period">/year</span>
                </div>
                <p class="plan-desc">Complete inventory management system with dedicated social media marketing & client acquisition support.</p>
                <ul class="plan-features">
                    <li>Unlimited locations</li>
                    <li>Advanced analytics & reporting</li>
                    <li>Custom integrations</li>
                    <li>Dedicated account manager</li>
                    <li>API access & supplier portal</li>
                    <li>📱 Social Media Ads Management</li>
                    <li>👥 Client Acquisition via Social Media</li>
                    <li>📊 Marketing Strategy & Campaign Support</li>
                    <li>24/7 Priority support</li>
                </ul>
                <a href="<?php echo e(route('register')); ?>" class="btn-plan btn-plan-white">Start Free Trial</a>
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.85rem; color: rgba(255,255,255,0.7); font-weight: 600;">✨ Renewal Rate: <span style="color: #10b981; font-size: 1rem;">Tsh 100,000/year</span></div>
                <p style="margin-top: 8px; font-size: 0.78rem; color: rgba(255,255,255,0.5);">Significantly discounted rate for years 2, 3, and beyond</p>
            </div>

        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section id="testimonials">
  <div class="container">
    <div class="testi-head">
      <div class="section-tag">Testimonials</div>
      <h2 class="section-title">Trusted by Operations Teams<br>Around the World</h2>
      <p class="section-sub">Here's what businesses like yours say after switching to ZENITH.</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card fade-in">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">ZENITH cut our inventory discrepancies by 94%. What used to take our team three days now takes a single afternoon. It's not an upgrade — it's a complete transformation.</p>
        <div class="testi-author">
          <div class="testi-avatar">ST</div>
          <div><div class="testi-name">Sarah Kazinja</div><div class="testi-role"> NovaTech Supplies</div></div>
        </div>
      </div>
      <div class="testi-card fade-in delay-1">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">As a small business owner, I was skeptical. But ZENITH's onboarding was painless and the interface is intuitive. I have clarity I never had before — without hiring an extra person.</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background: #0891b2;">JA</div>
          <div><div class="testi-name">Jennifer </div><div class="testi-role">Mkulima foundation </div></div>
        </div>
      </div>
      <div class="testi-card fade-in delay-2">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">The supplier management module alone justified the subscription. Our procurement cycles shortened by 40% in the first quarter. The support team is world-class.</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background: #7c3aed;">DC</div>
          <div><div class="testi-name">David </div><div class="testi-role">Logistics </div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact">
  <div class="container">
    <div class="contact-layout">
      <div class="contact-info">
        <div class="section-tag">Get in Touch</div>
        <h2 class="section-title">Ready to See<br>ZENITH in Action?</h2>
        <p class="section-sub">Book a personalized 30-minute demo. Our specialists will show you exactly how ZENITH fits your business — no generic walkthroughs.</p>

        <div class="contact-detail" style="margin-top: 32px;">
          <div class="contact-detail-icon">📞</div>
          <div>
            <div class="contact-detail-label">Call Us</div>
            <div class="contact-detail-val">+255 676 605 605</div>
          </div>
        </div>
        <div class="contact-detail">
          <div class="contact-detail-icon">✉️</div>
          <div>
            <div class="contact-detail-label">Email Us</div>
            <div class="contact-detail-val">hello@zenith.io</div>
          </div>
        </div>
        <div class="contact-detail">
          <div class="contact-detail-icon">🕐</div>
          <div>
            <div class="contact-detail-label">Support Hours</div>
            <div class="contact-detail-val">24/7 · Always On</div>
          </div>
        </div>

        <div style="margin-top: 36px; background: var(--cobalt-ultra); border: 1px solid var(--cobalt-mid); border-radius: 14px; padding: 24px;">
          <div style="font-weight: 700; color: var(--ink); font-size: 0.9rem; margin-bottom: 6px;">🎁 Limited-Time Offer</div>
          <div style="font-size: 0.85rem; color: var(--ink-faint); line-height: 1.6;">Start with an annual plan today and receive 3 months free — plus a dedicated onboarding specialist at no extra cost.</div>
        </div>
      </div>

      <div class="contact-form-wrap fade-in">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--ink); margin-bottom: 24px; letter-spacing: -0.02em;">Request Your Demo</h3>
        <form onsubmit="handleSubmit(event)">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input class="form-input" type="text" placeholder="Jane" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input class="form-input" type="text" placeholder="Smith" required>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Work Email</label>
            <input class="form-input" type="email" placeholder="jane@company.com" required>
          </div>
          <div class="form-group">
            <label class="form-label">Company Name</label>
            <input class="form-input" type="text" placeholder="Acme Corp" required>
          </div>
          <div class="form-group">
            <label class="form-label">Company Size</label>
            <select class="form-select">
              <option value="">Select team size...</option>
              <option>1–10 employees</option>
              <option>11–50 employees</option>
              <option>51–200 employees</option>
              <option>200+ employees</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Tell us about your needs</label>
            <textarea class="form-textarea" placeholder="What inventory challenges are you solving?"></textarea>
          </div>
          <button type="submit" class="submit-btn" id="submit-btn">Book My Demo →</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="#" class="nav-logo">
          <div class="logo-mark">Z</div>
          ZENITH
        </a>
        <p>Built for businesses that move fast. ZENITH is the inventory management platform trusted by 1,200+ companies in 20+ countries.</p>
      </div>
      <div>
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="#features">Features</a></li>
          <li><a href="#modules">Modules</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <!-- <li><a href="#">API Docs</a></li>
          <li><a href="#">Changelog</a></li> -->
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="#">About</a></li>
          <!-- <li><a href="#">Blog</a></li>
          <li><a href="#">Careers</a></li> -->
          <li><a href="#contact">Contact</a></li>
          <!-- <li><a href="#">Press</a></li> -->
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <!-- <li><a href="#">Privacy Policy</a></li> -->
          <li><a href="<?php echo e(route('index.route')); ?>">Terms of Service</a></li>
          <!-- <li><a href="#">Security</a></li>
          <li><a href="#">GDPR</a></li> -->
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 ZENITH 📦. All rights reserved.</span>
      <div style="display: flex; gap: 20px;">
        <a href="#">LinkedIn</a>
        <a href="#">Twitter</a>
        <a href="#">GitHub</a>
      </div>
    </div>
  </div>
</footer>

<script>
  // Sticky nav shadow
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  });

  // Scroll fade-in
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.12 });
  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

  // Module switcher data
  const modules = [
    {
      tag: 'Administration',
      title: 'Inventory Control System',
      desc: 'Gain full visibility across all stock locations. Set reorder points, track expiry dates, and manage bundles — all in one place. ZENITH handles the complexity so your team can focus on growth.',
      stat1: '98%', label1: 'Inventory Accuracy',
      stat2: '4h', label2: 'Avg. Onboarding'
    },
    {
      tag: 'Operations',
      title: 'Order Management System',
      desc: 'Process thousands of orders with automated workflows. From PO to delivery, every step is tracked, logged, and optimized. Eliminate manual errors and delight customers with on-time fulfillment.',
      stat1: '3×', label1: 'Faster Processing',
      stat2: '99%', label2: 'On-Time Delivery'
    },
    {
      tag: 'Finance',
      title: 'Financial Reporting',
      desc: 'Real-time P&L by item, category, and location. Export audit-ready reports and connect directly to your accounting software. Make capital allocation decisions backed by live inventory data.',
      stat1: '$2M+', label1: 'Avg. Cost Savings',
      stat2: '100%', label2: 'Audit Compliant'
    },
    {
      tag: 'Customer',
      title: 'Customer Portal',
      desc: 'Give B2B customers a branded self-service experience. Let them place orders, check stock availability, and track shipments in real time — reducing your team\'s support load by up to 60%.',
      stat1: '60%', label1: 'Support Reduction',
      stat2: '4.9★', label2: 'Customer Rating'
    }
  ];

  function setActiveModule(el, idx) {
    document.querySelectorAll('.module-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    const m = modules[idx];
    const visual = document.getElementById('modules-visual');
    visual.style.opacity = '0';
    visual.style.transform = 'translateY(8px)';
    setTimeout(() => {
      document.getElementById('mod-tag').textContent = m.tag;
      document.getElementById('mod-title').textContent = m.title;
      document.getElementById('mod-desc').textContent = m.desc;
      document.getElementById('mod-stats').innerHTML = `
        <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
          <div style="font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--cobalt);">${m.stat1}</div>
          <div style="font-size: 0.75rem; color: var(--ink-faint); margin-top: 4px;">${m.label1}</div>
        </div>
        <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 16px;">
          <div style="font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--cobalt);">${m.stat2}</div>
          <div style="font-size: 0.75rem; color: var(--ink-faint); margin-top: 4px;">${m.label2}</div>
        </div>`;
      visual.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      visual.style.opacity = '1';
      visual.style.transform = 'translateY(0)';
    }, 150);
  }

  // Form submit
  function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-btn');
    btn.textContent = '✓ Request Sent! We\'ll be in touch soon.';
    btn.style.background = '#16a34a';
    btn.disabled = true;
    setTimeout(() => {
      btn.textContent = 'Book My Demo →';
      btn.style.background = '';
      btn.disabled = false;
    }, 5000);
  }

  // Counter animation
  function animateCounters() {
    document.querySelectorAll('.trust-num').forEach(el => {
      const text = el.textContent;
      const num = parseFloat(text.replace(/[^0-9.]/g, ''));
      const suffix = text.replace(/[0-9.]/g, '');
      let start = 0;
      const duration = 1800;
      const step = timestamp => {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = (num < 10 ? (num * eased).toFixed(1) : Math.floor(num * eased)) + suffix;
        if (progress < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    });
  }

  const heroObserver = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) { animateCounters(); heroObserver.disconnect(); }
  }, { threshold: 0.5 });
  heroObserver.observe(document.querySelector('.hero-trust'));
</script>
</body>
</html>
<?php /**PATH C:\rstoresV1R\zenith\resources\views/front/about_master.blade.php ENDPATH**/ ?>