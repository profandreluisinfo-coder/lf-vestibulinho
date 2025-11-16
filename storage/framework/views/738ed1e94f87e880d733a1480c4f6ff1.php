

<?php $__env->startSection('page-title', config('app.name') . ' ' . config('app.year') . ' | Convocação para Matrícula'); ?>

<?php $__env->startPush('datatable-styles'); ?>
    <!-- datatables -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')); ?>">
    <!-- // datatables -->
<?php $__env->stopPush(); ?>

<?php $__env->startSection('dash-content'); ?>
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-broadcast-pin me-2"></i>Convocação para matrícula</h4>
            <?php if(!empty($countResults)): ?>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setNewCall">
                    <i class="bi bi-plus-circle me-1"></i> Nova Chamada
                </a>
            <?php endif; ?>
        </div>
        
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-striped freezed-table align-middle">
                <thead>
                    <tr class="table-success">
                        <th scope="col">Chamada</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Candidatos</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__empty_1 = true; $__currentLoopData = $callLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $callList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th><?php echo e($callList->number); ?></th> <!-- Número da chamada -->
                            <td><?php echo e(\Carbon\Carbon::parse($callList->date)->format('d/m/Y')); ?></td>
                            <!-- Data da chamada -->
                            <td><?php echo e(\Carbon\Carbon::parse($callList->time)->format('H:i')); ?></td>
                            <!-- Hora da chamada -->
                            <th><?php echo e($callList->calls_count); ?></th> <!-- Quantidade de convocados -->
                            <td>
                                <span class="badge bg-<?php echo e($callList->status == 'pending' ? 'warning' : 'success'); ?>">
                                    <?php echo e($callList->status == 'pending' ? 'Pendente' : 'Finalizada'); ?>

                                </span>
                            </td> <!-- Status da chamada -->
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Botão de excluir -->
                                    <form id="delete-form-<?php echo e($callList->id); ?>"
                                        action="<?php echo e(route('callings.destroy', $callList->id)); ?>" method="POST"
                                        class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?php echo e($callList->id); ?>)">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>

                                    <!-- Botão de detalhes -->
                                    <button class="btn btn-sm btn-secondary text-white" data-bs-toggle="collapse"
                                        data-bs-target="#details-<?php echo e($callList->id); ?>" aria-expanded="false"
                                        aria-controls="details-<?php echo e($callList->id); ?>">
                                        <i class="bi bi-info-circle"></i> Detalhes
                                    </button>

                                    <!-- Botão de finalizar -->
                                    <?php if($callList->status === 'pending'): ?>
                                        <form id="finalize-form-<?php echo e($callList->id); ?>"
                                            action="<?php echo e(route('callings.finalize', $callList->id)); ?>" method="POST"
                                            class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="button" class="btn btn-sm btn-success"
                                                onclick="confirmFinalize(<?php echo e($callList->id); ?>)">
                                                <i class="bi bi-check-circle"></i> Finalizar
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if($callList->status === 'completed'): ?>
                                        

                                        <a href="<?php echo e(route('callings.pdf', $callList->number)); ?>"
                                            class="btn btn-sm btn-primary text-white" target="_blank">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </td> <!-- Ações -->
                        </tr>
                        <tr class="collapse" id="details-<?php echo e($callList->id); ?>">
                            <td colspan="6" class="p-0">
                                <div class="my-3 px-2">
                                    <table class="table table-bordered w-100 datatable mb-0 table align-middle">
                                        <thead>
                                            <tr class="table-warning">
                                                <th scope="col">Classificação</th>
                                                <th scope="col">Inscrição</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">CPF</th>
                                                <th scope="col">PCD</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            <?php $__currentLoopData = $callList->calls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $user = $call->examResult->inscription->user;
                                                ?>
                                                <tr>
                                                    <th><?php echo e($call->examResult->ranking); ?></td>
                                                    <td><?php echo e($call->examResult->inscription_id); ?></td>
                                                    <th><?php echo e($user->social_name ?? $user->name); ?>

                                                    </th>
                                                    <td><?php echo e($user->cpf); ?></td>
                                                    <td>
                                                        <?php if($user->pne): ?>
                                                            <span class="badge bg-success"
                                                                title="Candidato PCD"><i class="bi bi-universal-access"></i></span>
                                                        <?php endif; ?>
                                                        
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">Nenhum registro encontrado. Possivelmente, nenhuma chamada foi lançada, ou, a
                                tabela de classificação está vazia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="modal fade" id="setNewCall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setLocalModalLabel">Lançar Nova Chamada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <form action="<?php echo e(route('callings.store')); ?>" method="POST" class="p-3"
                                id="setNewCallForm">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="number" class="form-label">Número da Chamada</label>
                                    <input type="number" name="number" id="number"
                                        class="form-control <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="1"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="limit" class="form-label">Quantidade de Candidatos</label>
                                    <input type="number" name="limit" id="limit"
                                        class="form-control <?php $__errorArgs = ['limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="pne_exam_result_ids" class="form-label">Selecionar Candidatos PCD
                                        (opcional)</label>
                                    <select name="manual_pcds[]" id="manual_pcds" class="form-select" multiple>
                                        <?php $__currentLoopData = $pneCandidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($candidate->id); ?>">
                                                <?php echo e($candidate->inscription->user->name); ?> — CPF:
                                                <?php echo e($candidate->inscription->user->cpf); ?> —
                                                Posição: <?php echo e($candidate->ranking); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="form-text">Segure Ctrl (ou Cmd) para selecionar múltiplos.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="form-label">Data de Comparecimento</label>
                                    <input type="date" name="date" id="date"
                                        class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="time" class="form-label">Hora de Comparecimento</label>
                                    <input type="time" name="time" id="time"
                                        class="form-control <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Registrar Chamada</button>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!$callLists->isEmpty()): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="bi bi-bar-chart-fill"></i> Convocados por Curso
            </button>
            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="bi bi-bar-chart-fill me-2"></i>Gráfico - Convocados por Curso</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <canvas id="convocadosChart" height="100"></canvas>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        </div>

                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('plugins'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
    <!-- Datatables -->
    <script src="<?php echo e(asset('assets/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')); ?>"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!-- PDF e Excel (para botões de exportação) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/rules/calling.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/swa/calls/delete.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/swa/calls/finalize.js')); ?>"></script>
    <!-- Importa o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Importa o plugin de datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const chartLabels = <?php echo json_encode($chartData['labels'], 15, 512) ?>;
        const chartData = <?php echo json_encode($chartData['data'], 15, 512) ?>;
        const total = chartData.reduce((acc, val) => acc + val, 0);

        chartLabels.push('Total');
        chartData.push(total);

        const fixedColors = ['#109618', '#000000', '#dc3912', '#3366cc'];

        const chartColors = chartLabels.map((label, index) => {
            if (label === 'Total') return '#888888'; // cinza para total
            return fixedColors[index % fixedColors.length];
        });

        const ctx = document.getElementById('convocadosChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Convocados',
                    data: chartData,
                    backgroundColor: chartColors,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        color: '#fff', // branco pra destacar dentro da barra
                        anchor: 'center', // centraliza na barra
                        align: 'center', // deixa no meio vertical
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                order: [
                    [0, 'asc']
                ], // Remove a ordenação padrão
                columnDefs: [{
                    orderable: false,
                    targets: [1, 2, 3, 4] // Colunas que não devem ser ordenáveis
                }],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                buttons: ["excel", "pdf", "print", "colvis"],
                responsive: true,
                autoWidth: true,
                lengthChange: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Tudo"]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip'
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/calls/private/create.blade.php ENDPATH**/ ?>