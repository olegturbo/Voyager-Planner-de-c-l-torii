<?php
session_start();
$pageTitle = "Destinații — Voyager";
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

    <div id="toastContainer" class="toast-container"></div>

    <nav class="navbar" id="navbar">
        <a class="nav-brand" href="index.php">✈ VOY<span>AGE</span>R</a>

        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">ACASĂ</a></li>
            <li><a href="about.php">DESPRE</a></li>
            <li><a href="destinations.php" class="active">DESTINAȚII</a></li>
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

        <section class="section">
            <div class="section-header">
                <div>
                    <div class="section-label">Explorează lumea</div>
                    <h2 class="section-title">Destinații de vis</h2>
                </div>
            </div>
            <div class="destinations-grid" id="destinationsGrid">
                <!-- Rendered by JS -->
            </div>
        </section>

        <section class="itinerary-section">
            <div class="section-header">
                <div>
                    <div class="section-label">Planifică-ți traseul</div>
                    <h2 class="section-title">Itinerar personalizat</h2>
                </div>
            </div>
            <div class="itinerary-timeline" id="itineraryList">
                <!-- Rendered by JS -->
            </div>
        </section>

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