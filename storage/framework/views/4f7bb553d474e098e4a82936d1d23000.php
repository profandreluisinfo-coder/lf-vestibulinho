

<?php $__env->startPush('metas'); ?>
    <?php if(app()->environment('local')): ?>
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    <?php endif; ?>
    <meta name="description"
        content="Área de acesso exclusivo para administradores do <?php echo e(config('app.name')); ?> <?php echo e(config('app.year')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-title', config('app.name') . ' ' . config('app.year') . ' | Painel Administrativo'); ?>

<?php $__env->startPush('head-scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body-class', 'bg-light'); ?>

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
                            <h2 class="h4 text-center"><?php echo e(config('app.name')); ?> <?php echo e(config('app.year')); ?></h2>
                        </header>
                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-person-gear me-2" aria-hidden="true"></i>
                                    Painel Administrativo
                                </span>
                            </h1>

                            <form id="form-login" method="POST" action="<?php echo e(route('login')); ?>" autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">E-mail</label>
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
                                    <label for="loginPassword" class="form-label">Senha</label>
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
                            </form>

                            <?php if(session('error')): ?>
                                <div class="alert alert-danger mb-3 mt-3 text-center"
                                    role="alert">
                                    <?php echo e(session('error')); ?>

                                </div>
                            <?php endif; ?>

                            <?php if(session('status')): ?>
                                <div class="alert alert-<?php echo e(session('status.alert-type')); ?> mb-3 mt-3 text-center"
                                    role="alert">
                                    <?php echo e(session('status.message')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <footer class="mini-footer mt-auto">
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
<?php echo $__env->make('layouts.home.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/auth/admin.blade.php ENDPATH**/ ?>