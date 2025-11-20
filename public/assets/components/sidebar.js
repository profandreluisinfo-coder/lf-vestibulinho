/* =========================================================
SIDEBAR & DROPDOWNS - VESTIBULINHO LF
Controla o comportamento da sidebar responsiva e
os menus dropdown internos.
========================================================= */

// Funções globais para toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar && overlay) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }
}

// Função global para toggleDropdown
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    if (!dropdown) return;

    const button = dropdown.previousElementSibling;
    const allDropdowns = document.querySelectorAll('.dropdown-menu-custom');
    const allButtons = document.querySelectorAll('.dropdown-toggle-custom');

    // Fecha todos os outros dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== id) {
            d.classList.remove('show');
        }
    });

    allButtons.forEach(b => {
        if (b !== button) {
            b.setAttribute('aria-expanded', 'false');
        }
    });

    // Toggle do dropdown clicado
    const isCurrentlyOpen = dropdown.classList.contains('show');

    if (isCurrentlyOpen) {
        dropdown.classList.remove('show');
        button.setAttribute('aria-expanded', 'false');
    } else {
        dropdown.classList.add('show');
        button.setAttribute('aria-expanded', 'true');
    }
}

// Quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function () {

    // Fechar sidebar ao clicar em links (mobile)
    const menuLinks = document.querySelectorAll('.menu-link, .dropdown-item-custom');
    menuLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });

    // Fechar sidebar ao redimensionar para desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });

    // Listener para overlay
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Listener para botão toggle
    const toggleBtn = document.querySelector('.menu-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }

    // Fechar offcanvas ao clicar em links internos
    const offcanvasLinks = document.querySelectorAll('#offcanvasMenu a');
    const offcanvasElement = document.getElementById('offcanvasMenu');

    offcanvasLinks.forEach(link => {
        link.addEventListener('click', function () {
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (bsOffcanvas) {
                bsOffcanvas.hide();
            }
        });
    });
});