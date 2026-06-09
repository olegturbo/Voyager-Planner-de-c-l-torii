<?php
session_start();
require_once 'php/function.php';
require_once 'php/auth.php';

$currentPage = 'contact';
$pageTitle = "Contact — Voyager";

$success = '';
$error = '';
$fields = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $fields  = compact('name', 'email', 'subject', 'message');

    if (!$name || !$email || !$message) {
        $error = 'Câmpurile Nume, Email și Mesaj sunt obligatorii.';
    } elseif (mb_strlen($name) < 2) {
        $error = 'Numele trebuie să aibă cel puțin 2 caractere.';
    } elseif (!validateEmail($email)) {
        $error = 'Adresa de email nu este validă.';
    } elseif (mb_strlen($message) < 10) {
        $error = 'Mesajul trebuie să conțină cel puțin 10 caractere.';
    } else {
        saveContactMessage([
            'name'    => sanitize($name),
            'email'   => sanitize($email),
            'subject' => sanitize($subject),
            'message' => sanitize($message),
        ]);
        $success = 'Mesajul tău a fost trimis cu succes! Te vom contacta în curând.';
        $fields  = [];
    }
}
?>
<!DOCTYPE html>
...
<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — Voyager</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
    <div id="toastContainer" class="toast-container"></div>

    <!-- includes/navbar.php -->
    <nav class="navbar">
        <a class="nav-brand" href="index.php">✈ VOY<span>AGE</span>R</a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php" <?= $currentPage === 'home' ? 'class="active"' : '' ?>>ACASĂ</a></li>
            <li><a href="about.php" <?= $currentPage === 'about' ? 'class="active"' : '' ?>>DESPRE</a></li>
            <li><a href="destinations.php" <?= $currentPage === 'destinations' ? 'class="active"' : '' ?>>DESTINAȚII</a></li>
            <li><a href="contact.php" <?= $currentPage === 'contact' ? 'class="active"' : '' ?>>CONTACT</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="dashboard.php" <?= $currentPage === 'dashboard' ? 'class="active"' : '' ?>>TABLOU DE BORD</a></li>
                <li><a href="logout.php">DECONECTARE</a></li>
            <?php else: ?>
                <li><a href="login.php" <?= $currentPage === 'login' ? 'class="active"' : '' ?>>AUTENTIFICARE</a></li>
                <li><a href="register.php" <?= $currentPage === 'register' ? 'class="active"' : '' ?>>ÎNREGISTRARE</a></li>
            <?php endif; ?>
        </ul>
        <div class="nav-controls">
            <button class="btn-theme" id="themeBtn" onclick="toggleTheme()" title="Schimbă tema">
                <svg id="iconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
                <svg id="iconSun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                    <circle cx="12" cy="12" r="5" />
                    <line x1="12" y1="1" x2="12" y2="3" />
                    <line x1="12" y1="21" x2="12" y2="23" />
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                    <line x1="1" y1="12" x2="3" y2="12" />
                    <line x1="21" y1="12" x2="23" y2="12" />
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                </svg>
            </button>
            <button class="hamburger" onclick="toggleNav()" aria-label="Meniu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
    <main style="padding-top:var(--nav-h)">
        <div class="contact-page">
            <div class="contact-info">
                <div class="section-label" style="margin-bottom:0.5rem">📬 Scrie-ne</div>
                <h1>Contactează-ne</h1>
                <p>Ai întrebări sau sugestii pentru Voyager? Suntem bucuroși să te ascultăm.</p>

                <div class="contact-detail">
                    <div class="contact-detail-icon">✉️</div>
                    <div class="contact-detail-text"><strong>Email</strong>hello@voyager-travel.ro</div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">📍</div>
                    <div class="contact-detail-text"><strong>Locație</strong>Chișinău, Moldova</div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">⏰</div>
                    <div class="contact-detail-text"><strong>Program</strong>Lun–Vin, 09:00–18:00</div>
                </div>
            </div>

            <div>
                <div class="contact-form-card">
                    <div class="contact-form-title">✍️ Formular de contact</div>

                    <?php if ($success): ?>
                        <div style="background:rgba(74,103,65,0.12);border:1px solid rgba(74,103,65,0.3);border-radius:var(--radius);padding:1rem;margin-bottom:1.5rem;font-size:0.9rem;color:var(--moss)">
                            ✅ <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div style="background:rgba(192,57,43,0.1);border:1px solid rgba(192,57,43,0.3);border-radius:var(--radius);padding:1rem;margin-bottom:1.5rem;font-size:0.88rem;color:#C0392B">
                            ❌ <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php" id="contactForm" novalidate>
                        <div class="form-group">
                            <label class="form-label" for="name">Nume complet *</label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Ion Popescu"
                                value="<?= htmlspecialchars($fields['name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="email@exemplu.com"
                                value="<?= htmlspecialchars($fields['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="subject">Subiect</label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="Subiectul mesajului"
                                value="<?= htmlspecialchars($fields['subject'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="message">Mesaj *</label>
                            <textarea id="message" name="message" class="form-textarea"
                                placeholder="Mesajul tău..."><?= htmlspecialchars($fields['message'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%">✉️ Trimite mesajul</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <span class="footer-brand">✈VOYAGER</span>
        <span>© <?= date('Y') ?> Voyager Travel Planner. Proiect educațional.</span>
        <nav class="footer-links">
            <a href="index.php">ACASĂ</a>
            <a href="about.php">DESPRE</a>
            <a href="contact.php">CONTACT</a>
        </nav>
    </footer>
    <script src="script/script.js"></script>
</body>

</html>