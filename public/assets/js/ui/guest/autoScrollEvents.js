document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('autoScrollEvents');
    if (!container) return;

    const speed = 0.5;
    const delayRestart = 1000;
    let isPaused = false;
    let isResetting = false;

    function autoScroll() {
        if (isPaused || isResetting) return;

        const maxScrollTop = container.scrollHeight - container.clientHeight;

        container.scrollTop += speed;

        // chegou (ou passou) do final
        if (container.scrollTop >= maxScrollTop - 1) {
            isResetting = true;

            setTimeout(() => {
                container.scrollTop = 0;
                isResetting = false;
            }, delayRestart);
        }
    }

    // só ativa se houver overflow real
    if (container.scrollHeight <= container.clientHeight) return;

    setInterval(autoScroll, 20);

    container.addEventListener('mouseenter', () => isPaused = true);
    container.addEventListener('mouseleave', () => isPaused = false);

});