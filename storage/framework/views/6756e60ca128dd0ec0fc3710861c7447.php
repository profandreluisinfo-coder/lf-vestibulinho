

<?php $__env->startSection('page-title', ' Inscrição | Dados Pessoais' . ' | ' . config('app.name') . ' ' . config('app.year')); ?>

<?php $__env->startSection('forms'); ?>

  <form id="inscription" class="row g-4" action="<?php echo e(route('step.personal')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <h5 class="fw-semibold border-bottom pb-1">Dados Pessoais</h5>
    <div class="form-group col-md-3">
      <label for="cpf" class="form-label required">CPF do candidato</label>
      <input type="text" class="form-control <?php $__errorArgs = ['cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cpf" name="cpf"
        value="<?php echo e(old('cpf', session('step1.cpf'))); ?>">
      <?php $__errorArgs = ['cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-9">
      <label for="name" class="form-label required">Nome completo do candidato</label>
      <input name="name" id="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        value="<?php echo e(old('name', session('step1.name'))); ?>">
      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-12">
      <p><span class="fw-semibold required">Deseja ser tratado(a) pelo nome social/afetivo?</span></p>
      <p>
        <strong>Nome social:</strong> designação pela qual a pessoa travesti ou transexual se identifica e é socialmente
        reconhecida (como garantido pelo <a class="text-decoration-none"
          href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2016/decreto/d8727.htm" target="_blank"
          title="DECRETO Nº 8.727, DE 28 DE ABRIL DE 2016" tabindex="-1" data-bs-toggle="popover" data-bs-trigger="hover"
          data-bs-content="Dispõe sobre o uso do nome social e o reconhecimento da identidade de gênero de pessoas travestis e transexuais no âmbito da administração pública federal direta, autárquica e fundacional.">DECRETO
          Nº 8.727, DE 28 DE ABRIL DE 2016</a>).
      </p>
      <p>
        <strong>Nome afetivo:</strong> é aquele que os responsáveis legais pela criança ou adolescente pretendem tornar
        definitivo quando das alterações da respectiva certidão de nascimento. (<a class="text-decoration-none"
          href="https://www.al.sp.gov.br/repositorio/legislacao/lei/2018/lei-16785-03.07.2018.html" target="_blank"
          title="LEI Nº 16.785, DE 03 DE JULHO DE 2018" tabindex="-1" data-bs-toggle="popover" data-bs-trigger="hover"
          data-bs-content="Dispõe sobre o uso do nome afetivo nos cadastros das instituições escolares, de saúde ou de cultura e lazer para crianças e adolescentes que estejam sob guarda da família adotiva, no período anterior à destituição do pátrio poder familiar">LEI
          Nº 16.785, DE 03 DE JULHO DE 2018</a>)
      </p>
      <div class="form-check form-check-inline">
        <input class="form-check-input <?php $__errorArgs = ['socialNameOption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="radio"
          name="socialNameOption" id="radio1" value="1"
          <?php echo e(old('socialNameOption', session('step1.socialNameOption')) == 1 ? 'checked' : ''); ?>>
        <label class="form-check-label" for="socialNameOption">
          Sim
        </label>
      </div>

      <div class="form-check form-check-inline">
        <input class="form-check-input <?php $__errorArgs = ['socialNameOption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="radio"
          name="socialNameOption" id="radio2" value="2"
          <?php echo e(old('socialNameOption', session('step1.socialNameOption')) != 1 ? 'checked' : ''); ?>>
        <label class="form-check-label" for="socialNameOption">
          Não
        </label>
      </div>
      <?php $__errorArgs = ['socialNameOption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div id="socialName" class="form-group col-md-12 d-none">
      <label for="social_name" class="form-label">Nome social/afetivo do candidato </label>
      <input name="social_name" id="social_name" class="form-control <?php $__errorArgs = ['social_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        value="<?php echo e(old('social_name', session('step1.social_name'))); ?>" aria-describedby="socialName">
        <small id="socialName" class="form-text text-muted">Leia atentamente o item 4.10 do <a href="<?php echo e(asset('storage/' . $notice->file)); ?>" title="Clique para abrir o edital" target="_blank">edital</a>.</small>
      <?php $__errorArgs = ['social_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-5">
      <label for="gender" class="form-label required">Gênero</label>
      <select class="form-select <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gender" name="gender">
        <option value="" selected>...</option>
        <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($gender); ?>" <?php echo e(old('gender', session('step1.gender')) == $gender ? 'selected' : ''); ?>>
            <?php echo e($value); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-6">
      <label for="birth" class="form-label required">Nascimento</label>
      <input type="date" class="form-control <?php $__errorArgs = ['birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="birth" name="birth"
        value="<?php echo e(old('birth', session('step1.birth'))); ?>">
      <?php $__errorArgs = ['birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-4">
      <label for="nationality" class="form-label required">Nacionalidade</label>
      <select class="form-select <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nationality" name="nationality">
        <option value="" selected tabindex="-1">...</option>
        <?php $__currentLoopData = $nationalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nationality => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($nationality); ?>"
            <?php echo e(old('nationality', session('step1.nationality')) == $nationality ? 'selected' : ''); ?>>
            <?php echo e($value); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-7">
      <label for="doc_type" class="form-label required">Tipo de documento</label>
      <select class="form-select <?php $__errorArgs = ['doc_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="doc_type" name="doc_type">
        <option value="" selected>...</option>
        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($document); ?>"
            <?php echo e(old('doc_type', session('step1.doc_type')) == $document ? 'selected' : ''); ?>>
            <?php echo e($value); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php $__errorArgs = ['doc_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-3">
      <label for="doc_number" class="form-label required">Número do documento</label>
      <input type="text" class="form-control <?php $__errorArgs = ['doc_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="doc_number"
        name="doc_number" minlength="7" maxlength="11" value="<?php echo e(old('doc_number', session('step1.doc_number'))); ?>">
      <?php $__errorArgs = ['doc_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group col-md-3">
      <label for="phone" class="form-label required">Telefone do candidato</label>
      <input type="text" class="form-control phone-mask <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone"
        name="phone" value="<?php echo e(old('phone', session('step1.phone'))); ?>">
      <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
          <?php echo e($message); ?>

        </div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 d-flex justify-content-center">
    <button type="submit" class="btn btn-primary btn-sm w-auto">Avançar <i class="bi bi-arrow-right-circle ms-2"></i></button>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('assets/cleave/masks.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/interactions/name.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/interactions/popover.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/rules/user.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.forms.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/forms/personal.blade.php ENDPATH**/ ?>