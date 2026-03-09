<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel"><i class="bi bi-lock me-2"></i>Alterar
                    Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-end">
                    <button type="button" id="toggleAllPasswords" class="btn btn-sm btn-link text-decoration-none">
                        <i class="bi bi-eye"></i> Mostrar senhas
                    </button>
                </div>
                <form id="change-password" action="{{ route('update.password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="currentPassword" class="form-label required">Senha atual</label>

                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPassword"
                                class="form-control password-field @error('current_password') is-invalid @enderror">

                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="newPassword" class="form-label required">Nova senha</label>

                        <div class="input-group">
                            <input type="password" name="new_password" id="newPassword"
                                class="form-control password-strength-field password-field @error('new_password') is-invalid @enderror">

                            @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="progress mt-2" style="height:6px;">
                            <div class="progress-bar passwordStrength"></div>
                        </div>

                        <small class="text-muted passwordStrengthText"></small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label required">Confirmar senha</label>

                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control password-field @error('password_confirmation') is-invalid @enderror">

                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success submit-password" disabled>
                            <i class="bi bi-check-circle me-2"></i>Alterar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/ui/auth/toggleAllPasswords.js') }}"></script>
@endpush

@if (session('open_modal') === 'password')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
        });
    </script>
@endif