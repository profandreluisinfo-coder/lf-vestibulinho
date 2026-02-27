// alerta de exclusão de curso
export function confirmCourseDelete(id, name) {
    Swal.fire({
        title: 'Tem certeza?',
        text: ` Você realmente deseja excluir o curso "${name}"? Essa ação não poderá ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-course-form-${id}`).submit();
        }
    });
}