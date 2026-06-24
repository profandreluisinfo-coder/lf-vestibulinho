<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vestibulinho LF ' . config('app.year')) — EM Dr. Leandro Franceschini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/site/styles.css') }}" />
    @stack('styles')
</head>

<body>
    @include('partials.site.navbar')

    @include('shared.toasts')

    @yield('content')

    @include('partials.site.footer')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Scroll reveal ──────────────────────────────────────────
        const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObs.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.15
        });
        revealEls.forEach(el => revealObs.observe(el));

        // ── Sticky navbar shadow ────────────────────────────────────
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
        });

        // ── Active nav link on scroll ───────────────────────────────
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.navbar-custom .nav-link[href^="#"]');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(sec => {
                if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
            });
            navLinks.forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === '#' + current);
            });
        });

        // ── Mostrar erro ───────────────────────────────────────────
        function showError(msg) {
            const alert = document.getElementById('alertError');
            document.getElementById('alertMsg').textContent = msg;
            alert.classList.remove('hidden');
            alert.style.animation = 'none';
            requestAnimationFrame(() => {
                alert.style.animation = 'shake .4s ease';
            });
        }
    </script>
    <script src="{{ asset('assets/js/shared/toasts.js') }}"></script>
    @stack('scripts')
</body>

</html>
