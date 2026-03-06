export function showToasts() {

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', showToasts);
        return;
    }

    const shownMessages = {};
    const toasts = document.querySelectorAll('.toast');

    if (!toasts.length) return;

    toasts.forEach(toastEl => {

        const message = toastEl.dataset.message;
        const type = toastEl.dataset.type;
        const key = type + message;

        if (shownMessages[key]) {
            shownMessages[key].count++;
            shownMessages[key].badge.innerText =
                `(${shownMessages[key].count}x)`;

            toastEl.remove();
            return;
        }

        const strongEl =
            toastEl.querySelector('.toast-body strong');

        if (!strongEl) return;

        const badge = document.createElement('span');
        badge.className = 'ms-2 badge bg-light text-dark';

        strongEl.appendChild(badge);

        shownMessages[key] = {
            count: 1,
            badge
        };

        const isPersistent =
            toastEl.dataset.persistent === 'true';

        const toast = new bootstrap.Toast(toastEl, {
            autohide: !isPersistent,
            delay: 3000
        });

        toast.show();
    });
}