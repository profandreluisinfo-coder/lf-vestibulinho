// Scroll Reveal
document.addEventListener('DOMContentLoaded', function () {
    const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    reveals.forEach(el => observer.observe(el));
});

// Navbar Scrolled Effect
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar-custom');
    if (window.scrollY > 50) {
        navbar?.classList.add('scrolled');
    } else {
        navbar?.classList.remove('scrolled');
    }
});

// Mobile Menu Toggle
const toggleMenu = document.querySelector('[data-toggle="navbar"]');
const navbarCollapse = document.querySelector('.navbar-collapse');

if (toggleMenu) {
    toggleMenu.addEventListener('click', function () {
        navbarCollapse?.classList.toggle('show');
    });
}