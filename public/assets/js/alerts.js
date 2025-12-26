const shownMessages = {};

document.querySelectorAll('.toast').forEach(toastEl => {

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

    const badge = document.createElement('span');
    badge.className = 'ms-2 badge bg-light text-dark';
    badge.innerText = '';

    toastEl.querySelector('.toast-body strong')
        .appendChild(badge);

    shownMessages[key] = {
        count: 1,
        badge: badge
    };

    const isPersistent = toastEl.dataset.persistent === 'true';

    const toast = new bootstrap.Toast(toastEl, {
        autohide: !isPersistent,
        delay: 3000
    });

    toast.show();
});