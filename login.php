<?php
session_start();
require_once 'php/auth.php';
require_once 'php/function.php';


$currentPage = 'login';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = 'Completează toate câmpurile.';
    } elseif (!validateEmail($email)) {
        $error = 'Email invalid.';
    } else {
        $user = loginUser($email, $pass);
        if ($user) {
            setSession($user);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email sau parolă incorectă.';
        }
    }
}


if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Voyager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
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
        <div class="auth-page">
            <div class="auth-card">
                <div class="auth-logo">✈ VOY<span>AGE</span>R</div>
                <div class="auth-subtitle">Autentifică-te pentru a continua aventura</div>

                <?php if ($error): ?>
                    <div style="background:rgba(192,57,43,0.1);border:1px solid rgba(192,57,43,0.3);border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.88rem;color:#C0392B">
                        ❌ <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" novalidate>
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="email@exemplu.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Parolă</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem; display:inline-block; text-align:center;">Autentifică-te</button>
                </form>

                <div class="auth-footer">
                    Nu ai cont? <a href="register.php">Înregistrare</a>
                </div>
            </div>
        </div>
    </main>
    <script src="script/script.js"></script>
</body>

</html>