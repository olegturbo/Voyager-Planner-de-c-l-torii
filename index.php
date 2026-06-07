    <?php
    session_start();
    $pageTitle = "Voyager — Travel Planner";
    ?>
    <!DOCTYPE html>
    <html lang="ro" data-theme="light">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Voyager — Travel Planner</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,700&family=Cinzel:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/responsive.css">
    </head>

    <body>

        <!-- NAVBAR -->
        <nav class="navbar" id="navbar">
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

        <!-- HERO -->
        <section class="hero">

            <!-- Stânga: text -->
            <div class="hero-left">
                <p class="hero-eyebrow">
                    <span class="eyebrow-line"></span>
                    PLANIFICATORUL TĂU DE CĂLĂTORII
                </p>
                <h1 class="hero-title">
                    <span class="word-plain" data-i18n="hero_w1">Explorează</span><br>
                    <em class="word-italic" data-i18n="hero_w2">lumea</em><br>
                    <span class="word-plain" data-i18n="hero_w3">cu stil</span>
                </h1>
                <p class="hero-sub" data-i18n="hero_sub">
                    Planifică rute de neuitat, descoperă destinații ascunse
                    și poartă cu tine fiecare amintire a drumului.
                </p>
                <div class="hero-cta">
                    <a href="register.php" class="btn-primary" data-i18n="cta1">ÎNCEPE AVENTURA</a>
                    <a href="destinations.php" class="btn-outline" data-i18n="cta2">DESCOPERĂ DESTINAȚII</a>
                </div>
            </div>

            <!-- Dreapta: vizual -->
            <div class="hero-right">
                <!-- Fundalul ocean + gradient -->
                <div class="hero-bg"></div>

                <!-- Busola SVG animată -->
                <div class="compass-wrap">
                    <svg class="compass" viewBox="0 0 260 260" xmlns="http://www.w3.org/2000/svg">
                        <!-- Cercuri concentrice -->
                        <circle cx="130" cy="130" r="120" fill="none" stroke="rgba(168,213,226,0.15)" stroke-width="1" />
                        <circle cx="130" cy="130" r="100" fill="none" stroke="rgba(168,213,226,0.1)" stroke-width="1" stroke-dasharray="6 3" />
                        <circle cx="130" cy="130" r="80" fill="none" stroke="rgba(168,213,226,0.08)" stroke-width="1" />
                        <!-- Linii cardinale -->
                        <line x1="130" y1="10" x2="130" y2="50" stroke="rgba(232,213,176,0.3)" stroke-width="1" />
                        <line x1="130" y1="210" x2="130" y2="250" stroke="rgba(232,213,176,0.15)" stroke-width="1" />
                        <line x1="10" y1="130" x2="50" y2="130" stroke="rgba(232,213,176,0.15)" stroke-width="1" />
                        <line x1="210" y1="130" x2="250" y2="130" stroke="rgba(232,213,176,0.15)" stroke-width="1" />
                        <!-- Litere cardinale -->
                        <text x="130" y="20" text-anchor="middle" fill="rgba(232,213,176,0.7)" font-size="11" font-family="Cinzel,serif" letter-spacing="2">N</text>
                        <text x="130" y="255" text-anchor="middle" fill="rgba(232,213,176,0.3)" font-size="9" font-family="Cinzel,serif">S</text>
                        <text x="252" y="134" text-anchor="middle" fill="rgba(232,213,176,0.3)" font-size="9" font-family="Cinzel,serif">E</text>
                        <text x="9" y="134" text-anchor="middle" fill="rgba(232,213,176,0.3)" font-size="9" font-family="Cinzel,serif">V</text>
                        <!-- Ac N (alb/nisip) -->
                        <path d="M130 130 L136 72 L130 52 L124 72 Z" fill="rgba(232,213,176,0.9)" />
                        <!-- Ac S (desert) -->
                        <path d="M130 130 L136 188 L130 208 L124 188 Z" fill="rgba(201,98,47,0.75)" />
                        <!-- Centru -->
                        <circle cx="130" cy="130" r="6" fill="rgba(168,213,226,0.5)" stroke="rgba(232,213,176,0.4)" stroke-width="1.5" />
                        <!-- Diviziuni (PHP generează aceste SVG, dar le punem direct) -->
                        <?php for ($i = 0; $i < 72; $i++):
                            $a = $i * 5 * M_PI / 180;
                            $len = ($i % 18 === 0) ? 14 : ($i % 6 === 0 ? 8 : 4);
                            $r1 = 118;
                            $r2 = $r1 - $len;
                            $x1 = 130 + $r1 * sin($a);
                            $y1 = 130 - $r1 * cos($a);
                            $x2 = 130 + $r2 * sin($a);
                            $y2 = 130 - $r2 * cos($a);
                            $op = $i % 6 === 0 ? 0.4 : 0.15;
                        ?>
                            <line x1="<?= round($x1, 1) ?>" y1="<?= round($y1, 1) ?>"
                                x2="<?= round($x2, 1) ?>" y2="<?= round($y2, 1) ?>"
                                stroke="rgba(232,213,176,<?= $op ?>)" stroke-width="1" />
                        <?php endfor; ?>
                    </svg>
                </div>

                <!-- Statistici jos -->
                <div class="hero-stats">
                    <div class="stat">
                        <strong>240+</strong>
                        <span data-i18n="stat1">DESTINAȚII</span>
                    </div>
                    <div class="stat">
                        <strong>12K</strong>
                        <span data-i18n="stat2">CĂLĂTORI</span>
                    </div>
                    <div class="stat">
                        <strong>890</strong>
                        <span data-i18n="stat3">RUTE</span>
                    </div>
                </div>
            </div>

        </section>
        <!-- /hero -->


        <!-- FEATURES STRIP -->
        <section class="features">
            <div class="feature">
                <span class="feat-icon">🗺️</span>
                <h3 data-i18n="f1t">Itinerar vizual</h3>
                <p data-i18n="f1d">Construiește-ți călătoria zi cu zi, cu hărți și notițe personale.</p>
            </div>
            <div class="feature">
                <span class="feat-icon">🌍</span>
                <h3 data-i18n="f2t">Carduri destinații</h3>
                <p data-i18n="f2d">Inspirație din întreaga lume, organizate după sezon și buget.</p>
            </div>
            <div class="feature">
                <span class="feat-icon">📖</span>
                <h3 data-i18n="f3t">Jurnal de bord</h3>
                <p data-i18n="f3d">Salvează amintiri, fotografii și impresii din fiecare etapă.</p>
            </div>
        </section>


        <!--  FOOTER -->
        <footer class="footer">
            <span class="footer-brand">✈VOYAGER</span>
            <span>© <?= date('Y') ?> Voyager Travel Planner. Proiect educațional.</span>
            <nav class="footer-links">
                <a href="index.php">ACASĂ</a>
                <a href="about.php">DESPRE</a>
                <a href="contact.php">CONTACT</a>
            </nav>
        </footer>

        <script src="js/script.js"></script>
    </body>

    </html>