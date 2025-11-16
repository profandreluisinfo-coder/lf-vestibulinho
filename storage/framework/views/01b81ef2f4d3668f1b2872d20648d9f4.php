

<?php $__env->startPush('metas'); ?>
    <?php if(app()->environment('local')): ?>
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    <?php endif; ?>
    <meta name="description"
        content="Área de acesso exclusivo para candidatos do <?php echo e(config('app.name')); ?> <?php echo e($calendar->year); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar->year . ' | Área do Candidato'); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('partials.videos.back-login', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <main class="auth mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <article class="card shadow-sm">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
                            <h2 class="h3 text-center"><?php echo e(config('app.name')); ?> <?php echo e(config('app.year')); ?></h2>
                        </header>

                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-person-lock me-2" aria-hidden="true"></i>
                                    Área do Candidato
                                </span>
                            </h1>

                            <div>
                                <p class="text-center">Informe seus dados de acesso:</p>
                            </div>

                            <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <form id="form-login" method="POST" action="<?php echo e(route('login')); ?>" autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label required">E-mail</label>
                                    <input type="email" name="email"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="loginEmail"
                                        value="<?php echo e(old('email')); ?>" placeholder="exemplo@email.com" required
                                        autocomplete="username" aria-describedby="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> emailError <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div id="emailError" class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label required">Senha</label>
                                    <input type="password" name="password"
                                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="loginPassword"
                                        placeholder="••••••••" required autocomplete="current-password"
                                        aria-describedby="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> passwordError <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div id="passwordError" class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">Lembrar de mim</label>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                                        Entrar
                                    </button>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="<?php echo e(route('forgot.password')); ?>" class="text-decoration-none">Esqueceu a
                                        senha?</a>
                                    <span aria-hidden="true">
                                        <?php if($calendar?->isInscriptionOpen()): ?>
                                            |
                                            <a href="<?php echo e(route('register')); ?>" class="text-decoration-none">Registrar
                                                e-mail</a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <footer class="mini-footer">
        <?php echo $__env->make('home.mini-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </footer>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('plugins'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/auth/login.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.home.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/auth/login.blade.php ENDPATH**/ ?>