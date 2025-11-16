

<?php $__env->startSection('page-title', config('app.name') . ' | Calendário ' . $calendar->year); ?>

<?php $__env->startSection('dash-content'); ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar4-week me-2"></i>Cadastrar Calendário do Processo Seletivo <?php echo e($calendar->year); ?></h4>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">

          <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          
          <form method="post" action="<?php echo e(route('calendar.store')); ?>">
            <?php echo csrf_field(); ?>
            

            
            <div class="border-start border-primary border-4 ps-3 mb-4">
              <h5 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informações Básicas</h5>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="year" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar3 me-1"></i>Ano do Processo Seletivo
                  </label>
                  <input type="number" class="form-control <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="year" name="year" min="2026" value="<?php echo e(old('year', $calendar->year ?? '')); ?>"
                    placeholder="Ex: 2026">
                  <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>

            
            <div class="border-start border-success border-4 ps-3 mb-4">
              <h5 class="text-success mb-3"><i class="bi bi-pencil-square me-2"></i>Período de Inscrições</h5>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="inscription_start" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar-check me-1"></i>Data de Início
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['inscription_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="inscription_start" name="inscription_start"
                    value="<?php echo e(old('inscription_start', \Carbon\Carbon::parse($calendar->inscription_start)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['inscription_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-6">
                  <label for="inscription_end" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar-x me-1"></i>Data de Término
                  </label>
                  <input type="date" class="form-control <?php $__errorArgs = ['inscription_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="inscription_end" name="inscription_end"
                    value="<?php echo e(old('inscription_end', \Carbon\Carbon::parse($calendar->inscription_end)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['inscription_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>

            
            <div class="border-start border-warning border-4 ps-3 mb-4">
              <h5 class="text-warning mb-3"><i class="bi bi-file-earmark-text me-2"></i>Aplicação das Provas</h5>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="exam_location_publish" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-geo-alt me-1"></i>Divulgação dos Locais de Prova
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['exam_location_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="exam_location_publish" name="exam_location_publish"
                    value="<?php echo e(old('exam_location_publish', \Carbon\Carbon::parse($calendar->exam_location_publish)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['exam_location_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                  <label for="exam_date" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar-event me-1"></i>Data de Aplicação das Provas
                  </label>
                  <input type="date" class="form-control <?php $__errorArgs = ['exam_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="exam_date" name="exam_date" value="<?php echo e(old('exam_date', \Carbon\Carbon::parse($calendar->exam_date)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['exam_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                  <label for="answer_key_publish" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-key me-1"></i>Divulgação do Gabarito
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['answer_key_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="answer_key_publish" name="answer_key_publish"
                    value="<?php echo e(old('answer_key_publish', \Carbon\Carbon::parse($calendar->answer_key_publish)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['answer_key_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>

            
            <div class="border-start border-info border-4 ps-3 mb-4">
              <h5 class="text-info mb-3"><i class="bi bi-arrow-repeat me-2"></i>Revisão de Questões</h5>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="exam_revision_start" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar-check me-1"></i>Início do Prazo para Revisão
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['exam_revision_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="exam_revision_start" name="exam_revision_start"
                    value="<?php echo e(old('exam_revision_start', \Carbon\Carbon::parse($calendar->exam_revision_start)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['exam_revision_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="col-md-6">
                  <label for="exam_revision_end" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-calendar-x me-1"></i>Término do Prazo para Revisão
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['exam_revision_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="exam_revision_end" name="exam_revision_end"
                    value="<?php echo e(old('exam_revision_end', \Carbon\Carbon::parse($calendar->exam_revision_end)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['exam_revision_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>

            
            <div class="border-start border-danger border-4 ps-3 mb-4">
              <h5 class="text-danger mb-3"><i class="bi bi-trophy me-2"></i>Resultados e Matrículas</h5>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="final_result_publish" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-list-ol me-1"></i>Divulgação da Classificação Final
                  </label>
                  <input type="date"
                    class="form-control <?php $__errorArgs = ['final_result_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="final_result_publish" name="final_result_publish"
                    value="<?php echo e(old('final_result_publish', \Carbon\Carbon::parse($calendar->final_result_publish)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['final_result_publish'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                  <label for="enrollment_start" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-person-check me-1"></i>Cronograma de Matrícula - 1ª Chamada
                  </label>
                  <input type="date" class="form-control <?php $__errorArgs = ['enrollment_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="enrollment_start" name="enrollment_start"
                    value="<?php echo e(old('enrollment_start', \Carbon\Carbon::parse($calendar->enrollment_start)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['enrollment_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                  <label for="enrollment_end" class="form-label fw-semibold text-secondary small">
                    <i class="bi bi-people me-1"></i>Cronograma de Vagas Remanescentes
                  </label>
                  <input type="date" class="form-control <?php $__errorArgs = ['enrollment_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    id="enrollment_end" name="enrollment_end"
                    value="<?php echo e(old('enrollment_end', \Carbon\Carbon::parse($calendar->enrollment_end)->format('Y-m-d') ?? '')); ?>">
                  <?php $__errorArgs = ['enrollment_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>

            
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
              <a href="<?php echo e(route('calendar.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Cancelar
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Salvar Calendário
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/calendar/private/edit.blade.php ENDPATH**/ ?>