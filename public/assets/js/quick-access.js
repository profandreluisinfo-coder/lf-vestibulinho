document.querySelectorAll(".card").forEach((card) => {
    // Ignora cards inativos
    if (card.classList.contains('card-inactive')) {
        return;
    }
    
    const icon = card.querySelector(".quick-access-icon");
    
    card.addEventListener("mouseenter", () => {
        card.classList.add("shadow-lg");
        if (icon) icon.classList.add("scale-up");
    });
    
    card.addEventListener("mouseleave", () => {
        card.classList.remove("shadow-lg");
        if (icon) icon.classList.remove("scale-up");
    });
});