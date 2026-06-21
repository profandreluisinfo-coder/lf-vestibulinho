document.addEventListener('DOMContentLoaded', function () {
    const heroSection = document.querySelector('.cal-hero');
    if (!heroSection) {
        return;
    }

    const circles = [
        { el: heroSection.querySelector('.hero-circle-1'), factor: 0.035, usePosition: false },
        { el: heroSection.querySelector('.hero-circle-2'), factor: 0.05, usePosition: false },
        { el: heroSection.querySelector('.hero-circle-3'), factor: 0.08, usePosition: true }
    ].filter(item => item.el);

    let targetX = 0;
    let targetY = 0;
    let currentX = 0;
    let currentY = 0;

    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

    function update() {
        currentX += (targetX - currentX) * 0.12;
        currentY += (targetY - currentY) * 0.12;

        circles.forEach(({ el, factor, usePosition }) => {
            const offsetX = currentX * factor;
            const offsetY = currentY * factor;

            if (usePosition) {
                const baseTop = 50;
                const baseRight = 12;
                el.style.top = `calc(${baseTop}% + ${offsetY}px)`;
                el.style.right = `calc(${baseRight}% + ${offsetX}px)`;
            } else {
                el.style.transform = `translate3d(${offsetX}px, ${offsetY}px, 0)`;
            }
        });

        requestAnimationFrame(update);
    }

    heroSection.addEventListener('pointermove', function (event) {
        const rect = heroSection.getBoundingClientRect();
        const normalizedX = (event.clientX - rect.left) / rect.width - 0.5;
        const normalizedY = (event.clientY - rect.top) / rect.height - 0.5;

        targetX = clamp(normalizedX * 36, -24, 24);
        targetY = clamp(normalizedY * 32, -20, 20);
    });

    heroSection.addEventListener('pointerleave', function () {
        targetX = 0;
        targetY = 0;
    });

    const motionAllowed = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function createSparkle(target, x, y) {
        const sparkle = document.createElement('span');
        sparkle.className = 'calendar-sparkle';
        sparkle.style.left = `${x}px`;
        sparkle.style.top = `${y}px`;
        target.appendChild(sparkle);

        requestAnimationFrame(() => {
            sparkle.style.opacity = '1';
            sparkle.style.transform = 'translate(-50%, -50%) scale(1.2)';
        });

        window.setTimeout(() => {
            sparkle.style.opacity = '0';
            sparkle.style.transform = 'translate(-50%, -50%) scale(.4)';
        }, 160);

        window.setTimeout(() => sparkle.remove(), 420);
    }

    if (motionAllowed) {
        document.querySelectorAll('.tf-step .tf-node').forEach(node => {
            node.addEventListener('pointerenter', function () {
                const rect = node.getBoundingClientRect();
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                for (let i = 0; i < 3; i += 1) {
                    const offsetX = centerX + (Math.random() - 0.5) * rect.width * 0.45;
                    const offsetY = centerY + (Math.random() - 0.5) * rect.height * 0.45;
                    createSparkle(node, offsetX, offsetY);
                }
            });
        });

        const ctaButton = document.querySelector('.btn-cta-main');
        if (ctaButton) {
            ctaButton.addEventListener('mouseenter', function () {
                const rect = ctaButton.getBoundingClientRect();
                for (let i = 0; i < 5; i += 1) {
                    const x = rect.width * (0.2 + Math.random() * 0.6);
                    const y = rect.height * (0.2 + Math.random() * 0.6);
                    createSparkle(ctaButton, x, y);
                }
            });
        }
    }

    update();
});