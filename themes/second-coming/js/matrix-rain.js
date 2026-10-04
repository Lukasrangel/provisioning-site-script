document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.createElement('canvas');
    canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:-1;pointer-events:none;image-rendering:pixelated;transition:opacity 1s ease-out;';
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');

    let animationId = null;
    let isPaused = false;
const getMatrixColor = () => {
    return getComputedStyle(document.documentElement)
           .getPropertyValue('--matrix-color').trim() || '#0f0';
    };

    // 1. ÉTAT DYNAMIQUE (Lien avec le localStorage)
    const charSets = {
        matrix: "01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン",
        binary: "01",
        ascii: "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$+-*/=%\"'#&_(),.;:?!",
glitch: "█▓▒░", // Ta version originale : effet de texture et blocs
        hex: "0123456789ABCDEF" // Look pur hacking / mémoire système
    };

    let currentSet = localStorage.getItem('matrixChars') || 'matrix';
    let speedFactor = parseFloat(localStorage.getItem('matrixSpeed')) || 1;
    let matrix = charSets[currentSet];
   
    const fontSize = 16;
    let drops = [];

    const resize = () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        init();
    };

    const init = () => {
        drops = [];
        const cols = Math.floor(canvas.width / fontSize);
        for (let i = 0; i < cols; i++) {
            drops[i] = Math.random() * -100;
        }
    };

    function draw() {
        if (isPaused) return;

        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = getMatrixColor();
        ctx.font = `${fontSize}px monospace`;

        for (let i = 0; i < drops.length; i++) {
            const char = matrix[Math.floor(Math.random() * matrix.length)];
            const x = i * fontSize;
            const y = drops[i] * fontSize;

            ctx.fillText(char, x, y);

            if (y > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            // Application du multiplicateur de vitesse
            drops[i] += speedFactor;
        }

        animationId = requestAnimationFrame(draw);
    }

    // 2. ÉCOUTEURS D'ÉVÉNEMENTS PERSONNALISÉS (V1.3.1)
    document.addEventListener('matrix:update', (e) => {
        if (e.detail.speed) speedFactor = parseFloat(e.detail.speed);
        if (e.detail.chars) {
            currentSet = e.detail.chars;
            matrix = charSets[currentSet];
        }
    });

    // === Le reste de ta logique de fade/start/stop (inchangée mais compatible) ===
    const saved = localStorage.getItem('matrixAnimation');
    let shouldRun = saved !== 'stopped';

    resize();
    window.addEventListener('resize', resize);

    if (shouldRun) {
        draw();
    } else {
        drawOnceFrozen();
        fadeOutSmooth(800);
    }

    // [Tes fonctions drawOnceFrozen, fadeOutSmooth, fadeInAndResume restent ici...]
    // Note: Dans drawOnceFrozen, utilise 'matrix' au lieu de la chaîne en dur.

    document.addEventListener('matrix:stop', () => {
        fadeOutSmooth(1200);
        localStorage.setItem('matrixAnimation', 'stopped');
    });

    document.addEventListener('matrix:start', () => {
        fadeInAndResume();
        localStorage.setItem('matrixAnimation', 'running');
    });

    function drawOnceFrozen() {
        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.shadowColor = getMatrixColor();
        ctx.shadowBlur = 8;
        ctx.globalAlpha = 0.7;
        ctx.fillStyle = getMatrixColor();
        ctx.font = `${fontSize}px monospace`;
        for (let i = 0; i < drops.length; i++) {
            const char = matrix[Math.floor(Math.random() * matrix.length)];
            const x = i * fontSize;
            const y = drops[i] * fontSize;
            ctx.fillText(char, x, y);
        }
        ctx.shadowBlur = 0;
    }

    function fadeOutSmooth(duration = 1000) {
        isPaused = true;
        cancelAnimationFrame(animationId);
        animationId = null;
        let start = null;
        const fade = (timestamp) => {
            if (!start) start = timestamp;
            const progress = (timestamp - start) / duration;
            canvas.style.opacity = 1 - progress * 0.92;
            if (progress < 1) requestAnimationFrame(fade);
            else canvas.style.opacity = '0.08';
        };
        requestAnimationFrame(fade);
    }

    function fadeInAndResume() {
        isPaused = false;
        canvas.style.opacity = '0';
        let start = null;
        const fade = (timestamp) => {
            if (!start) start = timestamp;
            const progress = (timestamp - start) / 600;
            canvas.style.opacity = progress;
            if (progress < 1) requestAnimationFrame(fade);
            else {
                canvas.style.opacity = '1';
                draw();
            }
        };
        requestAnimationFrame(fade);
    }
});
