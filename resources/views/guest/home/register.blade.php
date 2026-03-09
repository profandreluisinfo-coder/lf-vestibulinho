<div class="modal fade" id="register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="registerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="registerLabel"><i
                        class="bi bi-person-plus animate__animated animate__fadeIn me-2"></i> Registrar Dados de Acesso
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <form id="form-register" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-end">
                        <button type="button" id="toggleAllPasswords" class="btn btn-sm btn-link text-decoration-none">
                            <i class="bi bi-eye"></i> Mostrar senhas
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label required">E-mail:</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="registerEmail" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label required">Senha:</label>
                        <input type="password" name="password"
                            class="form-control password-field password-strength-field @error('password') is-invalid @enderror"
                            id="registerPassword">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="progress mt-2" style="height:6px;">
                            <div class="progress-bar passwordStrength"></div>
                        </div>

                        <small class="text-muted passwordStrengthText"></small>
                    </div>
                    <div class="mb-3">
                        <label for="registerRepeatPassword" class="form-label required">Repetir
                            senha:</label>
                        <input type="password" name="password_confirmation" id="registerRepeatPassword"
                            class="form-control password-field @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-group col-md-12">
                            <small class="text-muted">
                                <b>ATENÇÃO:</b> Sua senha deve conter no <b>mínimo</b> 6 caracteres e no
                                <b>máximo</b> 8 caracteres, incluindo, <b>pelo menos</b>, uma letra
                                maiúscula,
                                uma letra minúscula <b>e</b> um número.
                            </small>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success submit-password" disabled>
                            <i class="bi bi-person-plus animate__animated animate__fadeIn me-1"></i>
                            Cadastrar
                        </button>
                    </div>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3 gap-2">
                        <a href="{{ route('login') }}" class="text-decoration-none">Já tenho registro</a>
                        <a href="{{ route('resend.email') }}" class="text-decoration-none">Não recebeu o
                            e-mail
                            de confirmação?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
