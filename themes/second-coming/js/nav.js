/**
 * Navigation JS - Focus Trap uniquement en mobile
 */
document.addEventListener('DOMContentLoaded', () => {
    const menu = document.querySelector('#primary-menu');
    const openBtn = document.querySelector('.open-menu');
    const closeBtn = document.querySelector('.close-menu');

    if (!menu || !openBtn || !closeBtn) return;

    // ==================== FOCUS TRAP UNIQUEMENT EN MOBILE ====================
    function trapFocus() {
        const focusable = menu.querySelectorAll('a, button, [tabindex="0"]');
        if (focusable.length === 0) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        menu.addEventListener('keydown', (e) => {
            if (e.key !== 'Tab') return;

            if (e.shiftKey) { // Shift + Tab
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else { // Tab normal
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    }

    // ==================== OUVERTURE / FERMETURE ====================
    menu.addEventListener('toggle', (event) => {
        if (event.newState === 'open') {
            openBtn.classList.add('open');
            closeBtn.classList.add('open');

            // Focus trap uniquement si on est en mobile
            if (window.innerWidth <= 992) {
                trapFocus();
                setTimeout(() => closeBtn.focus(), 50);
            }
        } else {
            openBtn.classList.remove('open');
            closeBtn.classList.remove('open');
            openBtn.focus();
        }
    });

    // ==================== SOUS-MENUS MOBILE ====================
    const parentLinks = menu.querySelectorAll('.menu-item-has-children > a');
    parentLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                const submenu = link.nextElementSibling;
                if (submenu && submenu.classList.contains('sub-menu')) {
                    e.preventDefault();
                    submenu.classList.toggle('submenu-open');
                    link.setAttribute('aria-expanded', submenu.classList.contains('submenu-open'));
                }
            }
        });
    });
});
