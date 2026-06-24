export function lockSubmitButton(form, text = "Aguarde...") {
    const $btn = $(form).find('button[type="submit"]');

    if (!$btn.length) return;

    $btn
        .prop("disabled", true)
        .html(`
            <span class="spinner-border spinner-border-sm me-1"
                  role="status"
                  aria-hidden="true"></span>
            ${text}
        `);
}