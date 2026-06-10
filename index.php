<?php
// ─────────────────────────────────────────────────────
// VOYAGER — index.php
// Entry point: serves all pages as a single PHP file.
// Auth state managed via PHP session + JSON files.
// ─────────────────────────────────────────────────────
session_start();
$pageTitle = "Voyager — Travel Planner";
?>
<!DOCTYPE html>
<html lang="ro" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Favicon placeholder -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✈️</text></svg>">
</head>

<body>

    <!-- ════════════════════════════════════════
     TOAST CONTAINER
════════════════════════════════════════ -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- ════════════════════════════════════════
     NAVIGATION
════════════════════════════════════════ -->
    <nav class="navbar">
        <a class="nav-brand" href="#" onclick="navigateTo('home')">
            ✈ VOY<span>AGE</span>R
        </a>

        <ul class="nav-links" id="navLinks">
            <li><a class="nav-link-item" data-page="home" href="#" onclick="navigateTo('home')" data-i18n="nav_home">Acasă</a></li>
            <li><a class="nav-link-item" data-page="about" href="#" onclick="navigateTo('about')" data-i18n="nav_about">Despre</a></li>
            <li><a class="nav-link-item" data-page="features" href="#" onclick="navigateTo('features')" data-i18n="nav_features">Destinații</a></li>
            <li class="nav-if-auth" style="display:none">
                <a class="nav-link-item" data-page="dashboard" href="#" onclick="navigateTo('dashboard')" data-i18n="nav_dashboard">Tablou de bord</a>
            </li>
            <li><a class="nav-link-item" data-page="contact" href="#" onclick="navigateTo('contact')" data-i18n="nav_contact">Contact</a></li>
            <li class="nav-if-guest">
                <a class="nav-link-item" data-page="login" href="#" onclick="navigateTo('login')" data-i18n="nav_login">Autentificare</a>
            </li>
            <li class="nav-if-guest">
                <a class="nav-link-item" data-page="register" href="#" onclick="navigateTo('register')" data-i18n="nav_register">Înregistrare</a>
            </li>
            <li class="nav-if-auth" style="display:none">
                <button onclick="handleLogout()" data-i18n="nav_logout">Deconectare</button>
            </li>
        </ul>

        <div class="nav-controls">
            <select class="lang-select" id="langSelect" onchange="changeLang(this.value)">
                <option value="ro">🇷🇴 RO</option>
                <option value="en">🇬🇧 EN</option>
                <option value="ru">🇷🇺 RU</option>
            </select>
            <button class="btn-icon" id="themeToggle" onclick="toggleTheme()" title="Toggle theme">🌙</button>
            <button class="btn-icon hamburger" onclick="toggleNav()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ════════════════════════════════════════
     MAIN
════════════════════════════════════════ -->
    <main>

        <!-- ┌──────────────────────────────────
       │  PAGE: HOME
       └────────────────────────────────── -->
        <div id="page-home" class="page active">

            <!-- HERO -->
            <section class="hero">
                <div class="hero-left">
                    <div class="hero-eyebrow" data-i18n="hero_eyebrow">Planificatorul tău de călătorii</div>
                    <h1 class="hero-title">
                        <span data-i18n="hero_title_1">Explorează</span><br>
                        <em data-i18n="hero_title_2">lumea</em><br>
                        <span data-i18n="hero_title_3">cu stil</span>
                    </h1>
                    <p class="hero-subtitle" data-i18n="hero_sub">
                        Planifică rute de neuitat, descoperă destinații ascunse și poartă cu tine fiecare amintire a drumului.
                    </p>
                    <div class="hero-cta">
                        <button class="btn btn-primary" onclick="navigateTo('register')" data-i18n="hero_btn1">Începe aventura</button>
                        <button class="btn btn-outline" onclick="navigateTo('features')" data-i18n="hero_btn2">Descoperă destinații</button>
                    </div>
                </div>

                <div class="hero-right">
                    <div class="hero-map-bg">
                        <!-- Compass SVG -->
                        <svg class="compass-svg" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="150" cy="150" r="140" stroke="rgba(168,213,226,0.2)" stroke-width="1" />
                            <circle cx="150" cy="150" r="120" stroke="rgba(168,213,226,0.15)" stroke-width="1" stroke-dasharray="8 4" />
                            <circle cx="150" cy="150" r="100" stroke="rgba(168,213,226,0.1)" stroke-width="1" />
                            <!-- Cardinal directions -->
                            <text x="150" y="24" text-anchor="middle" fill="rgba(232,213,176,0.8)" font-size="14" font-family="Cinzel,serif" letter-spacing="2">N</text>
                            <text x="150" y="285" text-anchor="middle" fill="rgba(232,213,176,0.5)" font-size="11" font-family="Cinzel,serif">S</text>
                            <text x="282" y="155" text-anchor="middle" fill="rgba(232,213,176,0.5)" font-size="11" font-family="Cinzel,serif">E</text>
                            <text x="18" y="155" text-anchor="middle" fill="rgba(232,213,176,0.5)" font-size="11" font-family="Cinzel,serif">V</text>
                            <!-- Compass needle N -->
                            <path d="M150 150 L158 80 L150 60 L142 80 Z" fill="#E8D5B0" opacity="0.9" />
                            <!-- Compass needle S -->
                            <path d="M150 150 L158 220 L150 240 L142 220 Z" fill="rgba(201,98,47,0.8)" />
                            <!-- Center -->
                            <circle cx="150" cy="150" r="8" fill="rgba(168,213,226,0.6)" stroke="rgba(232,213,176,0.4)" stroke-width="1.5" />
                            <!-- Tick marks -->
                            <?php
                            for ($i = 0; $i < 72; $i++) {
                                $angle = $i * 5;
                                $rad = $angle * M_PI / 180;
                                $len = ($i % 18 === 0) ? 16 : (($i % 6 === 0) ? 10 : 5);
                                $r1 = 138;
                                $r2 = $r1 - $len;
                                $x1 = 150 + $r1 * sin($rad);
                                $y1 = 150 - $r1 * cos($rad);
                                $x2 = 150 + $r2 * sin($rad);
                                $y2 = 150 - $r2 * cos($rad);
                                $op = ($i % 6 === 0) ? "0.5" : "0.2";
                                echo "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' stroke='rgba(232,213,176,$op)' stroke-width='1'/>";
                            }
                            ?>
                        </svg>
                    </div>
                    <div class="hero-badges">
                        <div class="stat-badge">
                            <strong>240+</strong>
                            <span data-i18n="stat1">Destinații</span>
                        </div>
                        <div class="stat-badge">
                            <strong>12K</strong>
                            <span data-i18n="stat2">Călători</span>
                        </div>
                        <div class="stat-badge">
                            <strong>890</strong>
                            <span data-i18n="stat3">Rute</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FEATURES STRIP -->
            <section class="features-strip">
                <div class="feature-item">
                    <div class="feature-icon">🗺️</div>
                    <h3 data-i18n="feat1_title">Itinerar vizual</h3>
                    <p data-i18n="feat1_desc">Construiește-ți călătoria zi cu zi, cu hărți și notițe personale.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🌍</div>
                    <h3 data-i18n="feat2_title">Carduri destinații</h3>
                    <p data-i18n="feat2_desc">Inspirație din întreaga lume, organizate după sezon și buget.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📖</div>
                    <h3 data-i18n="feat3_title">Jurnal de bord</h3>
                    <p data-i18n="feat3_desc">Salvează amintiri, fotografii și impresii din fiecare etapă.</p>
                </div>
            </section>

        </div><!-- /page-home -->


        <!-- ┌──────────────────────────────────
       │  PAGE: DESTINATIONS / FEATURES
       └────────────────────────────────── -->
        <div id="page-features" class="page">

            <section class="section">
                <div class="section-header">
                    <div>
                        <div class="section-label" data-i18n="dest_label">Explorează lumea</div>
                        <h2 class="section-title" data-i18n="dest_title">Destinații de vis</h2>
                    </div>
                    <div class="nav-if-auth" style="display:none">
                        <button class="btn btn-primary" id="addDestBtn" onclick="openAddDest()">+ Adaugă destinație</button>
                    </div>
                </div>
                <div class="destinations-grid" id="destinationsGrid">
                    <!-- Rendered by JS -->
                </div>
            </section>

            <!-- ITINERARY -->
            <section class="itinerary-section">
                <div class="section-header">
                    <div>
                        <div class="section-label" data-i18n="itin_label">Planifică-ți traseul</div>
                        <h2 class="section-title" data-i18n="itin_title">Itinerar personalizat</h2>
                    </div>
                    <div class="nav-if-auth" style="display:none">
                        <button class="btn btn-outline" onclick="openAddItin()">+ <span data-i18n="btn_add_itin">Adaugă etapă</span></button>
                    </div>
                </div>
                <div class="itinerary-timeline" id="itineraryList">
                    <!-- Rendered by JS -->
                </div>
            </section>

        </div><!-- /page-features -->


        <!-- ┌──────────────────────────────────
       │  PAGE: ABOUT
       └────────────────────────────────── -->
        <div id="page-about" class="page">
            <div class="about-page">
                <div class="about-hero">
                    <h1><span data-i18n="about_title_1">Despre</span> <em data-i18n="about_title_2">Voyager</em></h1>
                    <p data-i18n="about_sub">Un companion de călătorie digital, construit cu pasiune pentru explorare.</p>
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
                    <div class="about-visual">🧭</div>
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
        </div><!-- /page-about -->


        <!-- ┌──────────────────────────────────
       │  PAGE: CONTACT
       └────────────────────────────────── -->
        <div id="page-contact" class="page">
            <div class="contact-page">

                <div class="contact-info">
                    <div class="section-label" style="margin-bottom:0.5rem">📬 Get in touch</div>
                    <h1 data-i18n="contact_title">Contactează-ne</h1>
                    <p data-i18n="contact_sub">Ai întrebări sau sugestii? Suntem bucuroși să te ascultăm.</p>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">✉️</div>
                        <div class="contact-detail-text">
                            <strong>Email</strong>
                            hello@voyager-travel.ro
                        </div>
                    </div>
                    <div class="contact-detail">
                        <div class="contact-detail-icon">📍</div>
                        <div class="contact-detail-text">
                            <strong>Locație</strong>
                            Chișinău, Moldova
                        </div>
                    </div>
                    <div class="contact-detail">
                        <div class="contact-detail-icon">⏰</div>
                        <div class="contact-detail-text">
                            <strong>Program</strong>
                            Lun–Vin, 09:00–18:00
                        </div>
                    </div>
                    <div class="contact-detail">
                        <div class="contact-detail-icon">🌐</div>
                        <div class="contact-detail-text">
                            <strong>Social</strong>
                            @voyager_travel
                        </div>
                    </div>
                </div>

                <div>
                    <div class="contact-form-card">
                        <div class="contact-form-title">✍️ Trimite un mesaj</div>
                        <form id="contactForm" onsubmit="handleContact(event)" novalidate>
                            <div class="form-group">
                                <label class="form-label" for="contactName" data-i18n="lbl_name">Nume complet</label>
                                <input type="text" id="contactName" class="form-input" data-i18n-placeholder="lbl_name" placeholder="Numele tău">
                                <span class="form-error" id="contactNameErr"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contactEmail" data-i18n="lbl_email">Email</label>
                                <input type="email" id="contactEmail" class="form-input" placeholder="email@exemplu.com">
                                <span class="form-error" id="contactEmailErr"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contactSubject" data-i18n="lbl_subject">Subiect</label>
                                <input type="text" id="contactSubject" class="form-input" data-i18n-placeholder="lbl_subject" placeholder="Subiectul mesajului">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contactMessage" data-i18n="lbl_message">Mesaj</label>
                                <textarea id="contactMessage" class="form-textarea" data-i18n-placeholder="lbl_message" placeholder="Mesajul tău..."></textarea>
                                <span class="form-error" id="contactMessageErr"></span>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%" data-i18n="btn_send">Trimite mesajul</button>
                        </form>
                    </div>
                </div>

            </div>
        </div><!-- /page-contact -->


        <!-- ┌──────────────────────────────────
       │  PAGE: LOGIN
       └────────────────────────────────── -->
        <div id="page-login" class="page">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="auth-logo">✈ VOY<span>AGE</span>R</div>
                    <div class="auth-subtitle" data-i18n="login_sub">Autentifică-te pentru a continua aventura</div>

                    <form id="loginForm" onsubmit="handleLogin(event)" novalidate>
                        <div class="form-group">
                            <label class="form-label" for="loginEmail" data-i18n="lbl_email">Email</label>
                            <input type="email" id="loginEmail" class="form-input" placeholder="email@exemplu.com">
                            <span class="form-error" id="loginEmailErr"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="loginPass" data-i18n="lbl_pass">Parolă</label>
                            <input type="password" id="loginPass" class="form-input" placeholder="••••••••">
                            <span class="form-error" id="loginPassErr"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem" data-i18n="btn_login">Autentifică-te</button>
                    </form>

                    <div class="auth-footer">
                        Nu ai cont? <a href="#" onclick="navigateTo('register')" data-i18n="nav_register">Înregistrare</a>
                    </div>
                </div>
            </div>
        </div><!-- /page-login -->


        <!-- ┌──────────────────────────────────
       │  PAGE: REGISTER
       └────────────────────────────────── -->
        <div id="page-register" class="page">
            <div class="auth-page">
                <div class="auth-card">
                    <div class="auth-logo">✈ VOY<span>AGE</span>R</div>
                    <div class="auth-subtitle" data-i18n="reg_sub">Creează-ți contul gratuit</div>

                    <form id="registerForm" onsubmit="handleRegister(event)" novalidate>
                        <div class="form-group">
                            <label class="form-label" for="regName" data-i18n="lbl_name">Nume complet</label>
                            <input type="text" id="regName" class="form-input" placeholder="Ion Popescu">
                            <span class="form-error" id="regNameErr"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="regEmail" data-i18n="lbl_email">Email</label>
                            <input type="email" id="regEmail" class="form-input" placeholder="email@exemplu.com">
                            <span class="form-error" id="regEmailErr"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="regPass" data-i18n="lbl_pass">Parolă</label>
                            <input type="password" id="regPass" class="form-input" placeholder="Min. 6 caractere">
                            <span class="form-error" id="regPassErr"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="regConfirm" data-i18n="lbl_confirm_pass">Confirmă parola</label>
                            <input type="password" id="regConfirm" class="form-input" placeholder="Repetă parola">
                            <span class="form-error" id="regConfirmErr"></span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem" data-i18n="btn_register">Înregistrează-te</button>
                    </form>

                    <div class="auth-footer">
                        Ai deja cont? <a href="#" onclick="navigateTo('login')" data-i18n="nav_login">Autentificare</a>
                    </div>
                </div>
            </div>
        </div><!-- /page-register -->


        <!-- ┌──────────────────────────────────
       │  PAGE: DASHBOARD (protected)
       └────────────────────────────────── -->
        <div id="page-dashboard" class="page">
            <div class="dashboard-page">

                <div class="dashboard-greeting">
                    <h1><span data-i18n="dash_welcome">Bun venit,</span> <span id="dashName"></span>! 👋</h1>
                    <p data-i18n="dash_sub">Iată un rezumat al călătoriilor tale</p>
                </div>

                <div class="dashboard-stats">
                    <div class="dash-stat" data-icon="🌍">
                        <strong id="statDests">0</strong>
                        <span data-i18n="stat1">Destinații</span>
                    </div>
                    <div class="dash-stat" data-icon="📅">
                        <strong id="statDays">0</strong>
                        <span>Zile planificate</span>
                    </div>
                    <div class="dash-stat" data-icon="🏳️">
                        <strong id="statCountries">0</strong>
                        <span>Țări</span>
                    </div>
                    <div class="dash-stat" data-icon="⭐">
                        <strong>4.8</strong>
                        <span>Rating mediu</span>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <div class="dash-panel-title">🌍 Destinațiile tale</div>
                            <button class="btn btn-ghost btn-sm" onclick="navigateTo('features')" data-i18n="nav_features">Vezi toate</button>
                        </div>
                        <div id="dashDestList"><!-- JS --></div>
                    </div>
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <div class="dash-panel-title">🗓️ Itinerar</div>
                            <button class="btn btn-ghost btn-sm" onclick="navigateTo('features');setTimeout(()=>document.getElementById('itineraryList').scrollIntoView({behavior:'smooth'}),300)">Detalii</button>
                        </div>
                        <div id="dashItinList"><!-- JS --></div>
                    </div>
                </div>

            </div>
        </div><!-- /page-dashboard -->

    </main><!-- /main -->


    <!-- ════════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
    <footer class="footer">
        <div>
            <div class="footer-brand">✈ VOY<span>AGE</span>R</div>
            <p>&copy; <?= date('Y') ?> Voyager Travel Planner. Proiect educațional.</p>
        </div>
        <div class="footer-links">
            <a href="#" onclick="navigateTo('home')">Acasă</a>
            <a href="#" onclick="navigateTo('about')">Despre</a>
            <a href="#" onclick="navigateTo('contact')">Contact</a>
        </div>
    </footer>


    <!-- ════════════════════════════════════════
     MODAL: ADD/EDIT DESTINATION
════════════════════════════════════════ -->
    <div class="modal-overlay" id="destModal">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('destModal')">✕</button>
            <div class="modal-title" id="destModalTitle">Adaugă destinație</div>
            <form id="destForm" onsubmit="saveDest(event)" novalidate>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label" data-i18n="lbl_city">Oraș</label>
                        <input type="text" id="destCity" class="form-input" placeholder="Santorini">
                    </div>
                    <div class="form-group">
                        <label class="form-label" data-i18n="lbl_country">Țară</label>
                        <input type="text" id="destCountry" class="form-input" placeholder="Grecia">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Tag / Regiune</label>
                        <select id="destTag" class="form-select">
                            <option>Europa</option>
                            <option>Africa</option>
                            <option>Asia</option>
                            <option>America Sud</option>
                            <option>America Nord</option>
                            <option>Oceania</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" data-i18n="lbl_days">Zile</label>
                        <input type="number" id="destDays" class="form-input" placeholder="5" min="1" max="365">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Emoji</label>
                        <input type="text" id="destEmoji" class="form-input" placeholder="🏛️">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label" data-i18n="lbl_budget">Buget</label>
                        <input type="text" id="destBudget" class="form-input" placeholder="1200€">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating (1-5)</label>
                        <input type="number" id="destRating" class="form-input" placeholder="5" min="1" max="5">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" data-i18n="lbl_desc">Descriere</label>
                    <textarea id="destDesc" class="form-textarea" style="min-height:80px" placeholder="Scurtă descriere a destinației..."></textarea>
                </div>
                <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:0.5rem">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('destModal')" data-i18n="btn_cancel">Anulează</button>
                    <button type="submit" class="btn btn-primary" data-i18n="btn_save">Salvează</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ════════════════════════════════════════
     MODAL: ADD/EDIT ITINERARY
════════════════════════════════════════ -->
    <div class="modal-overlay" id="itinModal">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('itinModal')">✕</button>
            <div class="modal-title" id="itinModalTitle">Adaugă etapă</div>
            <form id="itinForm" onsubmit="saveItin(event)" novalidate>
                <div style="display:grid;grid-template-columns:1fr 2fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label" data-i18n="lbl_day">Ziua</label>
                        <input type="number" id="itinDay" class="form-input" placeholder="1" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Titlu etapă</label>
                        <input type="text" id="itinTitle" class="form-input" placeholder="Zbor & Check-in">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Locație</label>
                        <input type="text" id="itinLocation" class="form-input" placeholder="Santorini">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tip activitate</label>
                        <select id="itinType" class="form-select">
                            <option>Transport</option>
                            <option>Explorare</option>
                            <option>Relaxare</option>
                            <option>Gastronomie</option>
                            <option>Cultură</option>
                            <option>Aventură</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" data-i18n="lbl_activity">Activitate / Descriere</label>
                    <textarea id="itinActivity" class="form-textarea" style="min-height:80px" placeholder="Ce vei face în această zi..."></textarea>
                </div>
                <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:0.5rem">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('itinModal')" data-i18n="btn_cancel">Anulează</button>
                    <button type="submit" class="btn btn-primary" data-i18n="btn_save">Salvează</button>
                </div>
            </form>
        </div>
    </div>


    <script src="js/script.js"></script>
</body>

</html>