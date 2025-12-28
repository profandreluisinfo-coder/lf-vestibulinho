<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel"><i class="bi bi-lock me-2"></i>Alterar
                    Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="change-password" action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Senha atual</label>
                        <input type="password" name="current_password" class="form-control"
                            id="currentPassword">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Nova senha</label>
                        <input type="password" name="new_password" class="form-control" id="newPassword">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar senha</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="password_confirmation">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Alterar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>