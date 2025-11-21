/**
 * Exibe uma mensagem de alerta com opções de tipo, titulo e texto.
 * 
 * @param {string} type - Tipo de alerta (success, error, warning, info)
 * @param {string} title - Título da mensagem
 * @param {string} message - Texto da mensagem
 * @param {string} [confirmText='Ok'] - Texto do botão de confirmação
 * @param {boolean} [showLoading=false] - Se true, carrega a mensagem de "carregando" antes de exibir a mensagem
 */
function showAlert(type, title, message, confirmText = 'Ok', showLoading = false) {
    if (showLoading) {
        Swal.fire({
            title: 'Processando...',
            text: 'Só mais um instante',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        setTimeout(() => {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                confirmButtonText: confirmText
            });
        }, 1000); // tempo de "carregando"
    } else {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            confirmButtonText: confirmText
        });
    }
}