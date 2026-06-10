<?php

/**
 * VOYAGER — dashboard.php
 * Protected page: requires active session.
 */
session_start();
require_once 'php/auth.php';
require_once 'php/functions.php';
requireLogin();

$user  = getSessionUser();
$items = getItems();
$dests = count($items);
$countries = count(array_unique(array_column($items, 'country')));
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Voyager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="toastContainer" class="toast-container"></div>

    <nav class="navbar">
        <a class="nav-brand" href="index.php">✈ VOY<span>AGE</span>R</a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Acasă</a></li>
            <li><a href="index.php#about">Despre</a></li>
            <li><a href="index.php#features">Destinații</a></li>
            <li><a href="dashboard.php" class="active">Tablou de bord</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="logout.php" onclick="return confirm('Te deconectezi?')">Deconectare</a></li>
        </ul>
        <div class="nav-controls">
            <select class="lang-select" id="langSelect" onchange="changeLang(this.value)">
                <option value="ro">🇷🇴 RO</option>
                <option value="en">🇬🇧 EN</option>
                <option value="ru">🇷🇺 RU</option>
            </select>
            <button class="btn-icon" id="themeToggle" onclick="toggleTheme()">🌙</button>
            <button class="btn-icon hamburger" onclick="toggleNav()">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main style="padding-top:var(--nav-h)">
        <div class="dashboard-page">

            <div class="dashboard-greeting">
                <h1>Bun venit, <?= htmlspecialchars($user['name']) ?>! 👋</h1>
                <p>Iată un rezumat al călătoriilor tale planificate.</p>
            </div>

            <!-- Stats -->
            <div class="dashboard-stats">
                <div class="dash-stat" data-icon="🌍">
                    <strong><?= $dests ?></strong>
                    <span>Destinații salvate</span>
                </div>
                <div class="dash-stat" data-icon="🏳️">
                    <strong><?= $countries ?></strong>
                    <span>Țări</span>
                </div>
                <div class="dash-stat" data-icon="👤">
                    <strong><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></strong>
                    <span>Utilizator</span>
                </div>
                <div class="dash-stat" data-icon="✉️">
                    <strong><?= count(json_decode(file_get_contents('data/messages.json'), true) ?? []) ?></strong>
                    <span>Mesaje trimise</span>
                </div>
            </div>

            <!-- Destinations list -->
            <div class="dash-grid">
                <div class="dash-panel">
                    <div class="dash-panel-header">
                        <div class="dash-panel-title">🌍 Destinații salvate (JSON)</div>
                        <a href="index.php" class="btn btn-ghost btn-sm">Adaugă</a>
                    </div>
                    <?php if (empty($items)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">🗺️</div>
                            <h3>Nicio destinație încă.</h3>
                        </div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--border)">
                                <span style="font-size:1.5rem"><?= htmlspecialchars($item['emoji'] ?? '🌍') ?></span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem"><?= htmlspecialchars($item['city'] ?? '') ?>, <?= htmlspecialchars($item['country'] ?? '') ?></div>
                                    <div style="font-size:0.78rem;color:var(--text-soft)"><?= htmlspecialchars($item['days'] ?? '') ?> zile · <?= htmlspecialchars($item['budget'] ?? '') ?></div>
                                </div>
                                <span class="badge badge-ocean" style="margin-left:auto"><?= htmlspecialchars($item['tag'] ?? '') ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="dash-panel">
                    <div class="dash-panel-header">
                        <div class="dash-panel-title">⚙️ Contul meu</div>
                    </div>
                    <div style="font-size:0.88rem;line-height:2">
                        <div>👤 <strong>Nume:</strong> <?= htmlspecialchars($user['name']) ?></div>
                        <div>✉️ <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
                        <div style="margin-top:1.5rem">
                            <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Ești sigur că vrei să te deconectezi?')">🚪 Deconectare</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="footer">
        <div>
            <div class="footer-brand">✈ VOY<span>AGE</span>R</div>
            <p>&copy; <?= date('Y') ?> Voyager Travel Planner.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>

</html>