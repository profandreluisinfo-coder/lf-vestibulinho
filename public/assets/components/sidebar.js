/* =========================================================
SIDEBAR & DROPDOWNS - VESTIBULINHO LF
Controla o comportamento completo da sidebar:
- Mobile: abrir/fechar com hambúrguer
- Desktop: recolher/expandir com logo
- Dropdowns: gerenciar menus internos
========================================================= */

// ==================== FUNÇÕES GLOBAIS ====================

/**
 * Toggle da sidebar no MOBILE (abrir/fechar)
 * Acionado pelo botão hambúrguer
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !overlay) return;

    // 🔴 MOBILE: remove collapsed antes de abrir
    if (window.innerWidth < 992) {
        sidebar.classList.remove('collapsed');
    }

    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// function toggleSidebar() {
//     const sidebar = document.getElementById('sidebar');
//     const overlay = document.getElementById('sidebarOverlay');
    
//     if (sidebar && overlay) {
//         sidebar.classList.toggle('show');
//         overlay.classList.toggle('show');
//     }
// }

/**
 * Fecha a sidebar no MOBILE
 * Acionado ao clicar no overlay ou em links
 */
function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }
}

/**
 * Toggle da sidebar no DESKTOP (recolher/expandir)
 * Acionado ao clicar na logo
 * Salva o estado no localStorage para persistir entre páginas
 */
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    
    if (!sidebar) return;
    
    const isCollapsed = sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

/**
 * Toggle de dropdowns internos da sidebar
 * Acionado ao clicar nos botões dropdown
 */
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

// ==================== INICIALIZAÇÃO ====================

document.addEventListener('DOMContentLoaded', function () {
    
    // ========== RESTAURA ESTADO COLLAPSED (DESKTOP) ==========
    const sidebar = document.getElementById('sidebar');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (isCollapsed && sidebar) {
        sidebar.classList.add('collapsed');
    }
    
    // ========== EVENTOS MOBILE ==========
    
    // Botão hambúrguer
    const toggleBtn = document.querySelector('.menu-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    // Overlay (fundo escuro)
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Fechar ao clicar em links (apenas mobile)
    const menuLinks = document.querySelectorAll('.menu-link, .dropdown-item-custom');
    menuLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });

    // Fechar ao redimensionar para desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });

    // ========== OFFCANVAS ==========
    
    // Fechar offcanvas ao clicar em links internos
    const offcanvasLinks = document.querySelectorAll('#offcanvasMenu a');
    const offcanvasElement = document.getElementById('offcanvasMenu');

    if (offcanvasElement) {
        offcanvasLinks.forEach(link => {
            link.addEventListener('click', function () {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
            });
        });
    }
});