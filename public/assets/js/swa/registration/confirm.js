function confirmFinalize() {

  const agree = document.getElementById('agree_terms');

  if (!agree.checked) {
    Swal.fire({
      icon: 'error',
      title: 'Atenção',
      text: 'Você precisa concordar com os termos do edital para finalizar.'
    });
    return; // PARA A EXECUÇÃO AQUI
  }

  Swal.fire({
    title: 'Confirmação Final',
    text: 'Você confirma que todos os dados foram preenchidos corretamente? Após finalizar a inscrição, nenhuma alteração será permitida.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sim, finalizar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {

      Swal.fire({
        title: 'Processando sua inscrição...',
        text: 'Por favor, aguarde alguns instantes.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      document.getElementById('finalize-inscription').submit();
    }
  });
}