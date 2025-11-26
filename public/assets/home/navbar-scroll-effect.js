/**
 * NAVBAR SCROLL EFFECT
 * 
 * Este script adiciona um efeito dinâmico à barra de navegação (navbar) 
 * quando o usuário rola a página.
 * 
 * FUNCIONALIDADES:
 * - Monitora o evento de scroll na janela
 * - Adiciona a classe 'navbar-scrolled' ao navbar quando o scroll 
 *   vertical ultrapassa 20 pixels
 * - Remove a classe quando o scroll retorna ao topo (menos de 20 pixels)
 * 
 * COMO USAR:
 * 1. Inclua este script em seu projeto
 * 2. Defina os estilos CSS para a classe '.navbar-scrolled'
 * 3. Personalize o valor do scroll (atualmente 20px) conforme necessário
 * 
 * EXEMPLO DE CSS:
 * .navbar-scrolled {
 *   background-color: #fff;
 *   box-shadow: 0 2px 10px rgba(0,0,0,0.1);
 *   padding: 10px 0;
 * }
 */

document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 20) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
});