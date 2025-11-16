document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card-animada");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = `${index * 0.1}s`; // efeito cascata
                    entry.target.classList.add("card-visivel");
                    observer.unobserve(entry.target); // só anima uma vez
                }
            });
        },
        {
            threshold: 0.1,
        }
    );

    cards.forEach((card) => observer.observe(card));
});