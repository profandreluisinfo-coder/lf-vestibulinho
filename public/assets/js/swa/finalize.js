// finalizar inscrição
  function confirmFinalize() {
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
        // Exibe o alerta de carregamento
        Swal.fire({
          title: 'Processando sua inscrição...',
          text: 'Por favor, aguarde alguns instantes.',
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Envia o formulário
        document.getElementById('finalize-inscription').submit();
      }
    });
  }