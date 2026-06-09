function doTranslate(lang) {
    const select = document.querySelector('.goog-te-combo');
    if (!select) {
        setTimeout(() => doTranslate(lang), 400);
        return;
    }
    select.value = lang === 'ro' ? '' : lang;
    select.dispatchEvent(new Event('change'));
}

function changeLang(lang) {
    state.lang = lang;
    localStorage.setItem('voy_lang', lang);
    applyLang();
    doTranslate(lang);
    if (state.currentPage === 'features') renderDestinations();
    if (state.currentPage === 'dashboard') renderDashboard();
}

// ─── STATE ────────────────────────────────────
let state = {
    lang: localStorage.getItem('voy_lang') || 'ro',
    theme: localStorage.getItem('voy_theme') || 'light',
    currentUser: null,
    currentPage: 'home',
    editingDestId: null,
    editingItinId: null,
};

// ─── JSON "DATABASE" (localStorage simulation) ─
function getUsers() { return JSON.parse(localStorage.getItem('voy_users') || '[]'); }
function saveUsers(u) { localStorage.setItem('voy_users', JSON.stringify(u)); }
function getDestinations() { return JSON.parse(localStorage.getItem('voy_destinations') || '[]'); }
function saveDestinations(d) { localStorage.setItem('voy_destinations', JSON.stringify(d)); }
function getItinerary() { return JSON.parse(localStorage.getItem('voy_itinerary') || '[]'); }
function saveItinerary(it) { localStorage.setItem('voy_itinerary', JSON.stringify(it)); }
function getSession() { return JSON.parse(localStorage.getItem('voy_session') || 'null'); }
function saveSession(u) { localStorage.setItem('voy_session', JSON.stringify(u)); }
function clearSession() { localStorage.removeItem('voy_session'); }

// ─── T() TRANSLATION HELPER ───────────────────
function t(key) {
    return (TRANSLATIONS[state.lang] && TRANSLATIONS[state.lang][key]) || key;
}

// ─── TOAST ───────────────────────────────────
function showToast(message, type = 'success', duration = 3500) {
    const container = document.getElementById('toastContainer');
    const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
    <span class="toast-icon">${icons[type] || icons.info}</span>
    <span class="toast-msg">${message}</span>
    <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
  `;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), duration);
}

// ─── THEME ───────────────────────────────────
function applyTheme() {
    document.documentElement.setAttribute('data-theme', state.theme);

    const iconMoon = document.getElementById('iconMoon');
    const iconSun = document.getElementById('iconSun');

    if (iconMoon && iconSun) {
        iconMoon.style.display = state.theme === 'dark' ? 'none' : '';
        iconSun.style.display = state.theme === 'dark' ? '' : 'none';
    }
}

function toggleTheme() {
    state.theme = state.theme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('voy_theme', state.theme);
    applyTheme();
}

// ─── LANGUAGE ────────────────────────────────
function applyLang() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        el.textContent = t(key);
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        el.placeholder = t(el.getAttribute('data-i18n-placeholder'));
    });
    const sel = document.getElementById('langSelect');
    if (sel) sel.value = state.lang;
}

function changeLang(lang) {
    state.lang = lang;
    localStorage.setItem('voy_lang', lang);
    applyLang();
    // Re-render dynamic content
    if (state.currentPage === 'features') renderDestinations();
    if (state.currentPage === 'dashboard') renderDashboard();
}

// ─── NAVIGATION ──────────────────────────────
function navigateTo(page) {
    const protected_pages = ['dashboard'];
    if (protected_pages.includes(page) && !state.currentUser) {
        showToast(t('protected_msg'), 'warning');
        navigateTo('login');
        return;
    }
    // Hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    const target = document.getElementById(`page-${page}`);
    if (target) {
        target.classList.add('active');
        state.currentPage = page;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    // Update nav active
    document.querySelectorAll('.nav-link-item').forEach(a => {
        a.classList.toggle('active', a.dataset.page === page);
    });
    // Close mobile menu
    document.getElementById('navLinks').classList.remove('open');
    // Render page content
    if (page === 'features') renderDestinations();
    if (page === 'dashboard') renderDashboard();
}

// ─── AUTH ─────────────────────────────────────
function handleLogin(e) {
    e.preventDefault();
    clearErrors('loginForm');
    const email = document.getElementById('loginEmail').value.trim();
    const pass = document.getElementById('loginPass').value;
    let valid = true;
    if (!email) { showError('loginEmail', t('validate_required')); valid = false; }
    else if (!/\S+@\S+\.\S+/.test(email)) { showError('loginEmail', t('validate_email')); valid = false; }
    if (!pass) { showError('loginPass', t('validate_required')); valid = false; }
    if (!valid) return;

    const users = getUsers();
    const user = users.find(u => u.email === email && u.password === pass);
    if (!user) { showToast(t('toast_login_err'), 'error'); return; }

    state.currentUser = user;
    saveSession(user);
    updateNavAuth();
    showToast(t('toast_login_ok'), 'success');
    document.getElementById('loginForm').reset();
    navigateTo('dashboard');
}

function handleRegister(e) {
    e.preventDefault();
    clearErrors('registerForm');
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const pass = document.getElementById('regPass').value;
    const confirm = document.getElementById('regConfirm').value;
    let valid = true;

    if (!name || name.length < 2) { showError('regName', t('validate_name')); valid = false; }
    if (!email || !/\S+@\S+\.\S+/.test(email)) { showError('regEmail', t('validate_email')); valid = false; }
    if (!pass || pass.length < 6) { showError('regPass', t('validate_pass_short')); valid = false; }
    if (pass !== confirm) { showError('regConfirm', t('validate_pass_match')); valid = false; }
    if (!valid) return;

    const users = getUsers();
    if (users.find(u => u.email === email)) { showToast(t('toast_reg_err'), 'error'); return; }

    const newUser = { id: Date.now(), name, email, password: pass, joined: new Date().toISOString() };
    users.push(newUser);
    saveUsers(users);
    state.currentUser = newUser;
    saveSession(newUser);
    updateNavAuth();
    showToast(t('toast_reg_ok'), 'success');
    document.getElementById('registerForm').reset();
    navigateTo('dashboard');
}

function handleLogout() {
    state.currentUser = null;
    clearSession();
    updateNavAuth();
    showToast(t('toast_logout'), 'info');
    navigateTo('home');
}

function updateNavAuth() {
    const loggedIn = !!state.currentUser;
    document.querySelectorAll('.nav-if-auth').forEach(el => {
        el.style.display = loggedIn ? '' : 'none';
    });
    document.querySelectorAll('.nav-if-guest').forEach(el => {
        el.style.display = loggedIn ? 'none' : '';
    });
}

// ─── FORM VALIDATION HELPERS ─────────────────
function showError(fieldId, msg) {
    const field = document.getElementById(fieldId);
    if (field) field.classList.add('error');
    const errEl = document.getElementById(fieldId + 'Err');
    if (errEl) { errEl.textContent = msg; errEl.classList.add('show'); }
}

function clearErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(el => el.classList.remove('error'));
    form.querySelectorAll('.form-error').forEach(el => { el.textContent = ''; el.classList.remove('show'); });
}

// ─── DESTINATIONS CRUD ────────────────────────
const DEFAULT_DESTINATIONS = [
    { id: 1, city: 'Santorini', country: 'Grecia', emoji: '🏛️', tag: 'Europa', days: 5, budget: '1200€', desc: 'Stânci albe, mare albastru și apusuri legendare.', rating: 5 },
    { id: 2, city: 'Marrakech', country: 'Maroc', emoji: '🕌', tag: 'Africa', days: 7, budget: '800€', desc: 'Piețe colorate, riads ascunse și arome de bazar.', rating: 4 },
    { id: 3, city: 'Kyoto', country: 'Japonia', emoji: '⛩️', tag: 'Asia', days: 10, budget: '2000€', desc: 'Temple antice, grădini zen și cireși în floare.', rating: 5 },
    { id: 4, city: 'Patagonia', country: 'Argentina', emoji: '🏔️', tag: 'America Sud', days: 14, budget: '2500€', desc: 'Ghețari spectaculoși și peisaje sălbatice unice.', rating: 5 },
];

function getOrInitDestinations() {
    let d = getDestinations();
    if (d.length === 0) {
        saveDestinations(DEFAULT_DESTINATIONS);
        return DEFAULT_DESTINATIONS;
    }
    return d;
}

function renderDestinations() {
    const grid = document.getElementById('destinationsGrid');
    if (!grid) return;
    const dests = getOrInitDestinations();
    const user = state.currentUser;
    if (dests.length === 0) {
        grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><div class="empty-icon">🗺️</div><h3>${t('no_dest')}</h3></div>`;
        return;
    }
    grid.innerHTML = dests.map(d => `
    <div class="dest-card" data-id="${d.id}">
      <div class="dest-card-img" style="background:${getGradient(d.tag)}">
        <span style="position:relative;z-index:2;font-size:3.5rem">${d.emoji}</span>
        <span class="dest-card-tag">${d.tag}</span>
      </div>
      <div class="dest-card-body">
        <h3>${d.city}</h3>
        <div class="dest-card-meta">
          <span>📍 ${d.country}</span>
          <span>📅 ${d.days} zile</span>
          <span>💰 ${d.budget}</span>
        </div>
        <p>${d.desc}</p>
        <div class="dest-card-footer">
          <div class="dest-rating">${'★'.repeat(d.rating)}${'☆'.repeat(5 - d.rating)}</div>
          ${user ? `<div class="dest-actions">
            <button class="btn btn-ghost btn-sm" onclick="openEditDest(${d.id})">${t('btn_edit')}</button>
            <button class="btn btn-danger btn-sm" onclick="deleteDest(${d.id})">${t('btn_delete')}</button>
          </div>` : ''}
        </div>
      </div>
    </div>
  `).join('');
    // Update add button text
    const addBtn = document.getElementById('addDestBtn');
    if (addBtn) addBtn.textContent = `+ ${t('btn_add_dest')}`;
    const addBtnH = document.getElementById('addDestBtnHero');
    if (addBtnH) addBtnH.style.display = user ? '' : 'none';
}

function getGradient(tag) {
    const gradients = {
        'Europa': 'linear-gradient(135deg, #1B4F72, #2E86AB)',
        'Africa': 'linear-gradient(135deg, #C9622F, #E8845A)',
        'Asia': 'linear-gradient(135deg, #4A6741, #6B9A61)',
        'America Sud': 'linear-gradient(135deg, #1A1208, #3D2B1F)',
        'America Nord': 'linear-gradient(135deg, #2C3E50, #4A6FA5)',
        'Oceania': 'linear-gradient(135deg, #006994, #0099CC)',
    };
    return gradients[tag] || 'linear-gradient(135deg, #1B4F72, #2E86AB)';
}

function openAddDest() {
    state.editingDestId = null;
    document.getElementById('destModalTitle').textContent = t('btn_add_dest');
    document.getElementById('destForm').reset();
    document.getElementById('destModal').classList.add('open');
}

function openEditDest(id) {
    const dest = getDestinations().find(d => d.id === id);
    if (!dest) return;
    state.editingDestId = id;
    document.getElementById('destModalTitle').textContent = t('btn_edit');
    document.getElementById('destCity').value = dest.city;
    document.getElementById('destCountry').value = dest.country;
    document.getElementById('destTag').value = dest.tag;
    document.getElementById('destDays').value = dest.days;
    document.getElementById('destBudget').value = dest.budget;
    document.getElementById('destDesc').value = dest.desc;
    document.getElementById('destEmoji').value = dest.emoji;
    document.getElementById('destRating').value = dest.rating;
    document.getElementById('destModal').classList.add('open');
}

function saveDest(e) {
    e.preventDefault();
    const city = document.getElementById('destCity').value.trim();
    const country = document.getElementById('destCountry').value.trim();
    const tag = document.getElementById('destTag').value;
    const days = parseInt(document.getElementById('destDays').value) || 1;
    const budget = document.getElementById('destBudget').value.trim();
    const desc = document.getElementById('destDesc').value.trim();
    const emoji = document.getElementById('destEmoji').value.trim() || '🌍';
    const rating = parseInt(document.getElementById('destRating').value) || 4;

    if (!city || !country) { showToast(t('validate_required'), 'error'); return; }

    let dests = getDestinations();
    if (state.editingDestId) {
        dests = dests.map(d => d.id === state.editingDestId ? { ...d, city, country, tag, days, budget, desc, emoji, rating } : d);
    } else {
        dests.push({ id: Date.now(), city, country, tag, days, budget, desc, emoji, rating });
    }
    saveDestinations(dests);
    closeModal('destModal');
    showToast(t('toast_saved'), 'success');
    renderDestinations();
    renderDashboard();
}

function deleteDest(id) {
    if (!confirm('Ești sigur?')) return;
    saveDestinations(getDestinations().filter(d => d.id !== id));
    showToast(t('toast_deleted'), 'success');
    renderDestinations();
    renderDashboard();
}

// ─── ITINERARY CRUD ──────────────────────────
const DEFAULT_ITINERARY = [
    { id: 1, day: 1, title: 'Zbor & Check-in', location: 'Santorini', activity: 'Zbor din București, transfer la hotel în Oia, plimbare de seară pe faleză', type: 'Transport' },
    { id: 2, day: 2, title: 'Caldera & Fira', location: 'Fira', activity: 'Vizitarea orașului Fira, museum arheologic, cruise pe caldera vulcanică', type: 'Explorare' },
    { id: 3, day: 3, title: 'Apus în Oia', location: 'Oia', activity: 'Prânz la restaurant local, sesiune foto la castelul Oia, apusul legendar', type: 'Relaxare' },
];

function renderItinerary() {
    const container = document.getElementById('itineraryList');
    if (!container) return;
    const items = getOrInitItinerary();
    if (items.length === 0) {
        container.innerHTML = `<div class="empty-state"><div class="empty-icon">📋</div><h3>${t('no_itin')}</h3></div>`;
        return;
    }
    container.innerHTML = items.map(item => `
    <div class="timeline-item" data-day="${item.day}" data-id="${item.id}">
      <div class="timeline-header">
        <div class="timeline-title">${item.title}</div>
        <span class="timeline-badge">${item.type}</span>
      </div>
      <div class="timeline-desc">
        <strong>📍 ${item.location}</strong> — ${item.activity}
      </div>
      ${state.currentUser ? `<div class="timeline-actions">
        <button class="btn btn-ghost btn-sm" onclick="openEditItin(${item.id})">${t('btn_edit')}</button>
        <button class="btn btn-danger btn-sm" onclick="deleteItin(${item.id})">${t('btn_delete')}</button>
      </div>` : ''}
    </div>
  `).join('');
}

function getOrInitItinerary() {
    let it = getItinerary();
    if (it.length === 0) { saveItinerary(DEFAULT_ITINERARY); return DEFAULT_ITINERARY; }
    return it;
}

function openAddItin() {
    state.editingItinId = null;
    document.getElementById('itinModalTitle').textContent = t('btn_add_itin');
    document.getElementById('itinForm').reset();
    document.getElementById('itinModal').classList.add('open');
}

function openEditItin(id) {
    const item = getItinerary().find(i => i.id === id);
    if (!item) return;
    state.editingItinId = id;
    document.getElementById('itinModalTitle').textContent = t('btn_edit');
    document.getElementById('itinDay').value = item.day;
    document.getElementById('itinTitle').value = item.title;
    document.getElementById('itinLocation').value = item.location;
    document.getElementById('itinActivity').value = item.activity;
    document.getElementById('itinType').value = item.type;
    document.getElementById('itinModal').classList.add('open');
}

function saveItin(e) {
    e.preventDefault();
    const day = parseInt(document.getElementById('itinDay').value) || 1;
    const title = document.getElementById('itinTitle').value.trim();
    const location = document.getElementById('itinLocation').value.trim();
    const activity = document.getElementById('itinActivity').value.trim();
    const type = document.getElementById('itinType').value;

    if (!title || !location) { showToast(t('validate_required'), 'error'); return; }

    let items = getItinerary();
    if (state.editingItinId) {
        items = items.map(i => i.id === state.editingItinId ? { ...i, day, title, location, activity, type } : i);
    } else {
        items.push({ id: Date.now(), day, title, location, activity, type });
    }
    items.sort((a, b) => a.day - b.day);
    saveItinerary(items);
    closeModal('itinModal');
    showToast(t('toast_saved'), 'success');
    renderItinerary();
    renderDashboard();
}

function deleteItin(id) {
    if (!confirm('Ești sigur?')) return;
    saveItinerary(getItinerary().filter(i => i.id !== id));
    showToast(t('toast_deleted'), 'success');
    renderItinerary();
    renderDashboard();
}

// ─── DASHBOARD ───────────────────────────────
function renderDashboard() {
    if (!state.currentUser) return;
    const nameEl = document.getElementById('dashName');
    if (nameEl) nameEl.textContent = state.currentUser.name.split(' ')[0];

    const dests = getOrInitDestinations();
    const itins = getOrInitItinerary();
    const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    setEl('statDests', dests.length);
    setEl('statDays', itins.length > 0 ? Math.max(...itins.map(i => i.day)) : 0);
    setEl('statCountries', [...new Set(dests.map(d => d.country))].length);

    // Render mini list
    const listEl = document.getElementById('dashDestList');
    if (listEl) {
        listEl.innerHTML = dests.slice(0, 4).map(d => `
      <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--border)">
        <span style="font-size:1.5rem">${d.emoji}</span>
        <div>
          <div style="font-weight:600;font-size:0.9rem">${d.city}, ${d.country}</div>
          <div style="font-size:0.78rem;color:var(--text-soft)">${d.days} zile · ${d.budget}</div>
        </div>
        <span class="badge badge-ocean" style="margin-left:auto">${d.tag}</span>
      </div>
    `).join('') || `<p style="color:var(--text-soft);font-size:0.88rem;padding:1rem 0">${t('no_dest')}</p>`;
    }

    const itinEl = document.getElementById('dashItinList');
    if (itinEl) {
        itinEl.innerHTML = getOrInitItinerary().slice(0, 3).map(i => `
      <div style="display:flex;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--border);align-items:flex-start">
        <span style="background:var(--ocean);color:white;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;flex-shrink:0;font-family:var(--font-display)">${i.day}</span>
        <div>
          <div style="font-weight:600;font-size:0.88rem">${i.title}</div>
          <div style="font-size:0.78rem;color:var(--text-soft)">${i.location}</div>
        </div>
        <span class="badge badge-desert" style="margin-left:auto;white-space:nowrap">${i.type}</span>
      </div>
    `).join('') || `<p style="color:var(--text-soft);font-size:0.88rem;padding:1rem 0">${t('no_itin')}</p>`;
    }
}

// ─── CONTACT FORM ────────────────────────────
function handleContact(e) {
    e.preventDefault();
    clearErrors('contactForm');
    const name = document.getElementById('contactName').value.trim();
    const email = document.getElementById('contactEmail').value.trim();
    const subject = document.getElementById('contactSubject').value.trim();
    const message = document.getElementById('contactMessage').value.trim();
    let valid = true;
    if (!name) { showError('contactName', t('validate_required')); valid = false; }
    if (!email || !/\S+@\S+\.\S+/.test(email)) { showError('contactEmail', t('validate_email')); valid = false; }
    if (!message) { showError('contactMessage', t('validate_required')); valid = false; }
    if (!valid) return;

    // Save to localStorage (simulated JSON)
    const messages = JSON.parse(localStorage.getItem('voy_messages') || '[]');
    messages.push({ id: Date.now(), name, email, subject, message, date: new Date().toISOString() });
    localStorage.setItem('voy_messages', JSON.stringify(messages));

    showToast(t('toast_contact'), 'success');
    document.getElementById('contactForm').reset();
}

// ─── MODAL HELPERS ───────────────────────────
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
}

// ─── HAMBURGER ───────────────────────────────
function toggleNav() {
    document.getElementById('navLinks').classList.toggle('open');
}

// ─── INIT ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Restore session
    const session = getSession();
    if (session) state.currentUser = session;

    applyTheme();
    applyLang();
    updateNavAuth();
    renderDestinations();
    renderItinerary();
    if (state.currentUser) renderDashboard();

    // Navigate to features page to show destinations on load if needed
    navigateTo('home');

    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });
});
