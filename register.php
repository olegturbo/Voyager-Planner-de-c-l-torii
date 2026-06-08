<?php
session_start();
require_once 'php/auth.php';
require_once 'php/function.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']     ?? '');
    $email   = trim($_POST['email']    ?? '');
    $pass    = $_POST['password']      ?? '';
    $confirm = $_POST['confirm']       ?? '';

    if (!$name || !$email || !$pass || !$confirm) {
        $error = 'Completează toate câmpurile.';
    } elseif (mb_strlen($name) < 2) {
        $error = 'Numele trebuie să aibă cel puțin 2 caractere.';
    } elseif (!validateEmail($email)) {
        $error = 'Email invalid.';
    } elseif (strlen($pass) < 6) {
        $error = 'Parola trebuie să aibă cel puțin 6 caractere.';
    } elseif ($pass !== $confirm) {
        $error = 'Parolele nu coincid.';
    } elseif (findUserByEmail($email)) {
        $error = 'Acest email este deja înregistrat.';
    } else {
        $user = registerUser($name, $email, $pass);
        setSession($user);
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare — Voyager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="navbar">
        <a class="nav-brand" href="index.php">✈ VOY<span>AGE</span>R</a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php" class="active">ACASĂ</a></li>
            <li><a href="about.php">DESPRE</a></li>
            <li><a href="destinations.php">DESTINAȚII</a></li>
            <li><a href="contact.php">CONTACT</a></li>
            <li><a href="login.php">AUTENTIFICARE</a></li>
            <li><a href="register.php">ÎNREGISTRARE</a></li>
        </ul>

        <div class="nav-controls">
            <button class="btn-theme" id="themeBtn" onclick="toggleTheme()" title="Schimbă tema">
                <svg id="iconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
        </div>
    </nav>
    <main style="padding-top:var(--nav-h)">
        <div class="auth-page">
            <div class="auth-card">
                <div class="auth-logo">✈ VOY<span>AGE</span>R</div>
                <div class="auth-subtitle">Creează-ți contul gratuit</div>

                <?php if ($error): ?>
                    <div style="background:rgba(192,57,43,0.1);border:1px solid rgba(192,57,43,0.3);border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.88rem;color:#C0392B">
                        ❌ <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php" novalidate>
                    <div class="form-group">
                        <label class="form-label" for="name">Nume complet</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Ion Popescu"
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="email@exemplu.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Parolă</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Min. 6 caractere">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="confirm">Confirmă parola</label>
                        <input type="password" id="confirm" name="confirm" class="form-input" placeholder="Repetă parola">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem;display:inline-block; text-align:center;">Înregistrează-te</button>
                </form>

                <div class="auth-footer">
                    Ai deja cont? <a href="login.php">Autentificare</a>
                </div>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>