// alerta de alteração de status de publicação de pergunta de faq
export function confirmFaqPublish(id, question) {
    Swal.fire({
        title: 'Confirmar publicação',
        text: `Você deseja alterar o status da publicação da pergunta: "${question}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`publish-faq-form-${id}`).submit();
        }
    });
}