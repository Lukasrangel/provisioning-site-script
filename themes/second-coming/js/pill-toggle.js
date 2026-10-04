document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('matrix-control');
    if (!btn) return;

    // --- 1. ÉTAT INITIAL ET RÉCUPÉRATION DATA ---
    let state = localStorage.getItem('matrixAnimation') || 'running';
    let currentSpeed = localStorage.getItem('matrixSpeed') || '1';
    let currentSet = localStorage.getItem('matrixChars') || 'matrix';

    // --- 2. CRÉATION DES ÉLÉMENTS UI ---
    const wrapper = document.createElement('div');
    wrapper.className = 'matrix-rabbit-wrapper';

    // Le Cadran (Settings Panel)
    const settingsPanel = document.createElement('div');
    settingsPanel.className = 'matrix-settings-panel';
    settingsPanel.innerHTML = `
        <button class="matrix-minimize-btn" title="Ranger">📱</button>
        <label>${matrixL10n.style}</label>
        <select id="matrix-set-selector">
            <option value="matrix">MATRIX (JP)</option>
            <option value="binary">BINARY</option>
            <option value="ascii">ASCII</option>
            <option value="glitch">GLITCH (BLOCKS)</option>
            <option value="hex">HEXADECIMAL</option>
        </select>
        <label>${matrixL10n.speed}</label>
        <input type="range" id="matrix-speed-slider" min="0.2" max="3" step="0.1" value="${currentSpeed}">
        <button id="matrix-reset-btn" class="matrix-btn-reset"> [ ${matrixL10n.reset} ] </button>
    `;

    // Le Lapin (Trigger)
    const rabbitTrigger = document.createElement('div');
    rabbitTrigger.className = 'white-rabbit-trigger';
    rabbitTrigger.innerHTML = '🐇';

    wrapper.appendChild(settingsPanel);
    wrapper.appendChild(rabbitTrigger);
    document.body.appendChild(wrapper);

    const selector = document.getElementById('matrix-set-selector');
    const slider = document.getElementById('matrix-speed-slider');
    selector.value = currentSet;

    // --- 3. LOGIQUE DE PERMUTATION (FURTIVITÉ) ---
    rabbitTrigger.addEventListener('click', () => {
        rabbitTrigger.style.display = 'none';    // Le lapin disparaît
        settingsPanel.style.display = 'flex';    // Le cadran apparaît
    });

    settingsPanel.querySelector('.matrix-minimize-btn').addEventListener('click', () => {
        settingsPanel.style.display = 'none';    // Le cadran disparaît
        rabbitTrigger.style.display = 'flex';    // Le lapin réapparaît
    });

    // --- 4. GESTION DU BOUTON PRINCIPAL (BLUE/RED PILL) ---
    function updateButton() {
        if (state === 'stopped') {
            btn.textContent = matrixL10n.takeRed;
            btn.classList.replace('blue', 'red');
            document.dispatchEvent(new CustomEvent('matrix:stop'));
            wrapper.style.display = 'none'; // Cache tout si off
        } else {
            btn.textContent = matrixL10n.takeBlue;
            btn.classList.replace('red', 'blue');
            document.dispatchEvent(new CustomEvent('matrix:start'));
            wrapper.style.display = 'flex'; // Affiche le wrapper
            // Reset l'état visuel sur le lapin par défaut
            settingsPanel.style.display = 'none';
            rabbitTrigger.style.display = 'flex';
        }
    }

    btn.addEventListener('click', () => {
        state = state === 'running' ? 'stopped' : 'running';
        localStorage.setItem('matrixAnimation', state);
        updateButton();
    });

    // --- 5. ÉCOUTEURS DES RÉGLAGES ---
    selector.addEventListener('change', (e) => {
        localStorage.setItem('matrixChars', e.target.value);
        document.dispatchEvent(new CustomEvent('matrix:update', { detail: { chars: e.target.value } }));
    });

    slider.addEventListener('input', (e) => {
        localStorage.setItem('matrixSpeed', e.target.value);
        document.dispatchEvent(new CustomEvent('matrix:update', { detail: { speed: e.target.value } }));
    });

    document.getElementById('matrix-reset-btn').addEventListener('click', () => {
        if (confirm(matrixL10n.reboot)) {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('matrix')) {
                    localStorage.removeItem(key);
                }
            });
            window.location.reload();
        }
    });

    // Lancement initial
    updateButton();
});
