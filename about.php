<?php
session_start();
$pageTitle = "Despre — Voyager";
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✈️</text></svg>">
</head>

<body>

    <nav class="navbar" id="navbar">
        <a class="nav-brand" href="index.php">✈ VOY<span>AGE</span>R</a>

        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">ACASĂ</a></li>
            <li><a href="about.php" class="active">DESPRE</a></li>
            <li><a href="destinations.php">DESTINAȚII</a></li>
            <li><a href="contact.php">CONTACT</a></li>
            <li><a href="login.php">AUTENTIFICARE</a></li>
            <li><a href="register.php">ÎNREGISTRARE</a></li>
        </ul>

        <div class="nav-controls">
            <select class="lang-select" id="langSelect" onchange="changeLang(this.value)">
                <option value="ro">RO</option>
                <option value="en">EN</option>
                <option value="ru">RU</option>
            </select>
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
            <button class="hamburger" onclick="toggleMobileNav()" aria-label="Meniu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>


    <main style="padding-top: var(--nav-h)">

        <div class="about-page">

            <div class="about-hero">
                <h1><span>Despre</span> <em>Voyager</em></h1>
                <p>Un companion de călătorie digital, construit cu pasiune pentru explorare.</p>
            </div>

            <div class="about-content">
                <div class="about-text">
                    <h2>Povestea noastră</h2>
                    <p>Voyager s-a născut dintr-o obsesie simplă: fiecare călătorie merită să fie planificată cu grijă și trăită cu intensitate. Am construit acest instrument pentru exploratorii moderni care vor mai mult decât un simplu bilet de avion.</p>
                    <p>Fie că visezi la temple din Kyoto, piețele din Marrakech sau gheții din Patagonia — Voyager îți oferă instrumentele să transformi visul în itinerar real.</p>
                    <p>Platforma este complet gratuită, fără reclame și fără complicații. Datele tale sunt ale tale, salvate local și exportabile oricând.</p>
                    <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap">
                        <div class="badge badge-desert">PHP</div>
                        <div class="badge badge-ocean">JSON Storage</div>
                        <div class="badge badge-moss">Multilingv</div>
                        <div class="badge badge-ocean">Responsive</div>
                    </div>
                </div>
                <div class="about-visual"><img src="img/foto.png" alt="foto"></div>
            </div>

            <div class="section" style="background:var(--sand-light);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;text-align:center">
                    <?php
                    $values = [
                        ['🌱', 'Autenticitate', 'Destinații verificate, recenzii reale, fără conținut sponsorizat.'],
                        ['🔒', 'Confidențialitate', 'Datele tale nu pleacă nicăieri. Totul e stocat local, pe dispozitivul tău.'],
                        ['🚀', 'Simplitate', 'Interfață curată, fără distractori. Focusul e pe călătoria ta.'],
                    ];
                    foreach ($values as $v): ?>
                        <div style="padding:2rem">
                            <div style="font-size:2.5rem;margin-bottom:1rem"><?= $v[0] ?></div>
                            <h3 style="font-family:var(--font-editorial);font-size:1.15rem;font-weight:700;margin-bottom:0.5rem;color:var(--ocean)"><?= $v[1] ?></h3>
                            <p style="font-size:0.88rem;color:var(--text-soft);line-height:1.6"><?= $v[2] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </main>


    <footer class="footer">
        <span class="footer-brand">✈ VOY<span>AGE</span>R</span>
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