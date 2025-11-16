function showAlert(type, title, message, confirmText = 'Ok', showLoading = false) {
    if (showLoading) {
        Swal.fire({
            title: 'Processando...',
            text: 'Aguarde um instante',
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