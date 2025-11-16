

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar->year . ' | Meus Dados'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/layouts/details.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('dash-content'); ?>

        <section>
            <article>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-clipboard-check text-primary fs-4 me-2 animate__animated animate__fadeIn"></i>
                    <h5 class="m-0 fw-semibold">Resumo da sua inscrição</h5>
                </div>
                <p class="text-muted mb-4">
                    Abaixo estão os dados principais da sua inscrição.
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th colspan="2">Dados do Candidato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Inscrição Nº</td>
                                <td><?php echo e(auth()->user()->inscription->id); ?></td>
                            </tr>
                            <tr>
                                <th>Nome Completo</td>
                                <td><?php echo e(auth()->user()->name); ?></td>
                            </tr>
                            <tr>
                                <th>CPF</td>
                                <td><?php echo e(auth()->user()->cpf); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if(auth()->user()->user_detail?->accessibility): ?>
                    <div class="table-responsive mt-4 mt-lg-1">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Necessidade de acessibilidade indicada pelo candidato:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo e(auth()->user()->user_detail?->accessibility); ?>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-warning border-0 mt-3 text-muted small text-break">
                            <i class="bi bi-exclamation-triangle me-0 me-md-1"></i>
                            <strong>Atenção!</strong>
                            O(a) candidato(a) portador de necessidades especiais deverá informar no período
                            de
                            inscrição
                            qual a
                            sua necessidade específica,
                            enviando e-mail com atestado médico anexo para
                            <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br"
                                class="text-decoration-none fw-semibold">
                                emdrleandrofranceschini@educacaosumare.com.br
                            </a>,
                            <strong>conforme o item 4.8 do edital</strong>.
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(auth()->user()->hasConfirmedCall()): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2">PARABÉNS, você foi convocado para efetuar sua matrícula!</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Chamada Nº:</td>
                                    <td><?php echo e($call?->call_number); ?></td>
                                </tr>
                                <tr>
                                    <th>Data:</td>
                                    <td><?php echo e(Carbon\Carbon::parse($call?->date)->format('d/m/Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Horário:</td>
                                    <td><?php echo e(Carbon\Carbon::parse($call?->time)->format('H:i')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-warning">
                            <span class="text-muted small">
                                <i class="bi bi-exclamation-triangle me-1 me-md-2"></i><strong>Atenção!</strong>
                                Compareça na data e horário informados para realizar sua matrícula.
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="#" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal"
                        data-bs-target="#fichaDeInscricao">
                        <i class="bi bi-search"></i> Inscrição
                    </a>

                    <?php if($settings->location): ?>
                    <a href="#" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#localDeProva">
                        <i class="bi bi-search"></i> Local de Prova
                    </a>
                    <?php endif; ?>

                    <?php if($settings->result): ?>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#resultadoDeProva">
                        <i class="bi bi-search"></i> Classificação
                    </a>
                    <?php endif; ?>

                    <?php if(auth()->user()->hasConfirmedCall()): ?>
                    <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#callDetailModal">
                        <i class="bi bi-search animate__animated animate__fadeIn"></i> Ver detalhes da convocação
                    </a>
                    <?php endif; ?>
                </div>

            </article>
        </section>

        <!-- Modal com todos os dados da inscrição do candidato -->
        <div class="modal" id="fichaDeInscricao">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-person-vcard me-2"></i> Ficha de Inscrição do Candidato</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">📄 Dados da Inscrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Inscrição Nº</th>
                                        <td><?php echo e($user->inscription->id); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Data</th>
                                        <td><?php echo e(\Carbon\Carbon::parse($user->inscription->created_at)->format('d/m/Y')); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">🧑‍💼 Identificação do Candidato</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>CPF</th>
                                        <td><?php echo e($user->cpf); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nome Completo</th>
                                        <td><?php echo e($user->social_name ?? $user->name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Gênero</th>
                                        <td><?php echo e($user->gender); ?></td>
                                    </tr>
                                    <tr>
                                        <th>E-mail</th>
                                        <td><?php echo e($user->email); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Telefone</th>
                                        <td><?php echo e($user->user_detail->phone); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">📑 Documentos Pessoais e Certidão</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Nacionalidade</th>
                                        <td><?php echo e($user->user_detail->nationality); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tipo de Documento</th>
                                        <td><?php echo e($user->user_detail->doc_type); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Número</th>
                                        <td><?php echo e($user->user_detail->doc_number); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Data de Nascimento</th>
                                        <td><?php echo e(\Carbon\Carbon::parse($user->birth)->format('d/m/Y')); ?></td>
                                    </tr>

                                    <?php if(!empty($user->user_detail->new_number)): ?>
                                        <tr>
                                            <th>Nº Certidão</th>
                                            <td><?php echo e($user->user_detail->new_number); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <th>Folhas</th>
                                            <td><?php echo e($user->user_detail->fls); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Livro</th>
                                            <td><?php echo e($user->user_detail->book); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nº Certidão</th>
                                            <td><?php echo e($user->user_detail->old_number); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Município</th>
                                            <td><?php echo e($user->user_detail->municipality); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">👨‍👩‍👧‍👦 Filiação / Responsável Legal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Mãe</th>
                                        <td><?php echo e($user->user_detail->mother); ?></td>
                                    </tr>
                                    <?php if($user->user_detail->mother_phone): ?>
                                        <tr>
                                            <th>Telefone da Mãe</th>
                                            <td><?php echo e($user->user_detail->mother_phone); ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if($user->user_detail->father): ?>
                                        <tr>
                                            <th>Pai</th>
                                            <td><?php echo e($user->user_detail->father); ?></td>
                                        </tr>
                                        <?php if($user->user_detail->father_phone): ?>
                                            <tr>
                                                <th>Telefone do Pai</th>
                                                <td><?php echo e($user->user_detail->father_phone); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($user->user_detail->responsible): ?>
                                        <tr>
                                            <th>Responsável Legal</th>
                                            <td><?php echo e($user->user_detail->responsible); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Parentesco</th>
                                            <td><?php echo e($user->user_detail->degree); ?></td>
                                        </tr>
                                        <?php if($user->user_detail->kinship): ?>
                                            <tr>
                                                <th>Descrição</th>
                                                <td><?php echo e($user->user_detail->kinship); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Telefone do Responsável</th>
                                            <td><?php echo e($user->user_detail->responsible_phone); ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <tr>
                                        <th>E-mail de Contato</th>
                                        <td><?php echo e($user->user_detail->parents_email); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">🎓 Escolaridade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Escola</th>
                                        <td><?php echo e($user->user_detail->school_name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>RA</th>
                                        <td><?php echo e($user->user_detail->school_ra); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Cidade</th>
                                        <td><?php echo e($user->user_detail->school_city); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Estado</th>
                                        <td><?php echo e($user->user_detail->school_state); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ano de Conclusão</th>
                                        <td><?php echo e($user->user_detail->school_year); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">🏠 Endereço</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>CEP</th>
                                        <td><?php echo e($user->user_detail->zip); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Rua</th>
                                        <td><?php echo e($user->user_detail->street); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Número</th>
                                        <td><?php echo e($user->user_detail->number); ?></td>
                                    </tr>
                                    <?php if($user->user_detail->complement): ?>
                                        <tr>
                                            <th>Complemento</th>
                                            <td><?php echo e($user->user_detail->complement); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Bairro</th>
                                        <td><?php echo e($user->user_detail->burgh); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Cidade</th>
                                        <td><?php echo e($user->user_detail->city); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Estado</th>
                                        <td><?php echo e($user->user_detail->state); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <?php if($user->user_detail?->accessibility): ?>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-info">
                                        <tr>
                                            <th colspan="2" class="fw-semibold">♿ Educação Especial</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Elegível</th>
                                            <td><?php echo e($user->user_detail?->accessibility ? 'SIM' : 'NÃO'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Necessidade</th>
                                            <td><?php echo e($user->user_detail->accessibility); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="alert alert-danger mt-2 text-muted small">
                                    <span class="fw-bold">Atenção!</span> O(a) candidato(a) com necessidades especiais
                                    deverá enviar
                                    atestado médico durante o periodo de inscrição conforme o item 4.8 do edital.
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">🤝 Programas Sociais e Outras Informações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Beneficiário Bolsa-Família</th>
                                        <td><?php echo e($user->user_detail?->nis ? 'SIM' : 'NÃO'); ?></td>
                                    </tr>
                                    <?php if($user->user_detail?->nis): ?>
                                        <tr>
                                            <th>NIS</th>
                                            <td><?php echo e($user->user_detail->nis); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Problema de Saúde / Alergia</th>
                                        <td><?php echo e($user->user_detail?->health ? 'SIM' : 'NÃO'); ?></td>
                                    </tr>
                                    <?php if($user->user_detail->health): ?>
                                        <tr>
                                            <th>Descrição</th>
                                            <td><?php echo e($user->user_detail->health); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <form action="<?php echo e(route('pdf')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('post'); ?>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-2"></i>Gerar PDF</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>

        <?php if($settings->location): ?>
        <!-- Modal de definição de local de prova -->
        <div class="modal" id="localDeProva">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-geo-alt-fill"></i> Local de Prova</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-person me-2"></i>Candidato:
                                        </td>
                                        <td class="w-75">
                                            <?php echo e(auth()->user()->social_name ? auth()->user()->social_name : auth()->user()->name); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-door-open me-2"></i>Local:
                                        </td>
                                        <td class="w-75">
                                            <div class="border-bottom mb-2"><?php echo e($exam?->location_name); ?></div>
                                            <div class="small text-muted"> <?php echo e($exam?->address); ?> </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-door-open me-2"></i>Sala:
                                        </td>
                                        <td class="w-75">
                                            <?php echo e($exam?->room_number); ?>

                                            <?php if($exam?->pne): ?>
                                                <div class="alert alert-warning mt-3 p-2">
                                                    <i class="bi bi-universal-access-circle"></i>
                                                    Sala de Atendimento Especializado.
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-calendar-event me-2"></i>Data:
                                        </td>
                                        <td class="w-75">
                                            <?php echo e(\Carbon\Carbon::parse($exam?->exam_date)->format('d/m/Y')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-clock me-2"></i>Hora:
                                        </td>
                                        <td class="w-75">
                                            <?php echo e(\Carbon\Carbon::parse($exam?->exam_time)->format('H:i')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-info-circle-fill"></i> Instruções:
                                        </td>
                                        <td class="w-75">
                                            <ul class="mb-0 small">
                                                <li>Chegue com <strong>30 minutos de antecedência</strong>.</li>
                                                <li>Leve documento com foto e caneta azul ou preta.</li>
                                                <li class="text-danger fw-bold">Não é permitido usar dispositivos
                                                    eletrônicos durante a prova.</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <a href="<?php echo e(route('user.card.pdf')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-download"></i> Baixar PDF
                            </a>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($settings->result): ?>
        <!-- Modal de exibição de classificação na prova-->
        <div class="modal" id="resultadoDeProva">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card border-success shadow-sm" id="result-card">
                            <div
                                class="card-header bg-success d-flex justify-content-between align-items-center text-white">
                                <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i> Resultado da Prova Objetiva</h5>
                                <span class="badge bg-light text-success">Ano <?php echo e($calendar->year); ?></span>
                            </div>

                            <div class="card-body text-center">
                                <h5 class="text-muted">Candidato(a)</h5>
                                <h4 class="fw-bold"><?php echo e($user->name); ?></h4>
                                <p class="mb-2">
                                    CPF <br><strong><?php echo e($user->cpf); ?></strong><br>
                                </p>

                                <hr class="my-4">

                                <h1 class="display-4 fw-bold text-success"><?php echo e($examResult?->score); ?></h1>
                                <p class="text-muted mb-1">Nota obtida</p>

                                <h2 class="text-primary mt-4"><?php echo e($examResult?->ranking); ?>º</h2>
                                <p class="mb-0">Classificação Geral</p>
                            </div>

                            <div class="card-footer text-muted small text-center">
                                Os critérios de desempate consideraram a idade do candidato (mais jovem tem prioridade).
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="<?php echo e(route('user.result.pdf')); ?>" class="btn btn-outline-primary me-2">
                                <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                            </a>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($call && auth()->user()->hasConfirmedCall()): ?>
        <!-- Modal de exibição de detalhes da convocação -->
        <div class="modal fade" id="callDetailModal" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="callDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-primary">
                    <div class="modal-header">
                        <h5 class="modal-title" id="callDetailModalLabel"><i class="bi bi-megaphone me-2"></i> Detalhes da Convocação</h5>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nome:</strong> <?php echo e(auth()->user()->social_name ?? auth()->user()->name); ?></p>
                        <p><strong>Chamada nº:</strong> <?php echo e($call?->call_number); ?></p>
                        <p><strong>Data:</strong> <?php echo e(Carbon\Carbon::parse($call?->date)->format('d/m/Y')); ?></p>
                        <p><strong>Horário:</strong> <?php echo e(Carbon\Carbon::parse($call?->time)->format('H:i')); ?></p>

                        <hr>

                        <h6 class="fw-bold text-primary">Local da Matrícula</h6>
                        <p class="mb-1">R. Geraldo de Souza, 221 - Jardim São Carlos</p>
                        <p class="mb-1">Sumaré - SP, 13170-232</p>
                        <p class="mb-1"><strong>Telefone:</strong> (19) 3873-2605</p>
                        <p class="mb-3"><strong>Horário de Funcionamento:</strong> 14:00 às 23:00</p>

                        <h6 class="fw-bold text-primary">INFORMAÇÕES IMPORTANTES!</h6>
                        <p>A falta de documentação ou não comparecimento na data e horário estabelecido acarretará na perda
                            da vaga,
                            portanto não se esqueça de comparecer no dia e horário indicado portando todos os documentos
                            previstos no
                            item <strong>7.4</strong> do edital. </p>
                        <ol class="docs-list">
                            <li>Declaração de Conclusão do Ensino Fundamental ou Histórico Escolar do Ensino Fundamental
                                (Original e
                                01 cópia);</li>
                            <li>01 foto 3x4;</li>
                            <li>Original e 01 cópia do documento de identidade (RG/CIN ou RNE para estrangeiro) atualizado e
                                com foto
                                que identifique o portador;</li>
                            <li>Original e 01 cópia do CPF;</li>
                            <li>Original e 01 cópia da certidão de nascimento;</li>
                            <li>Carteira de vacinação (Original e 01 cópia);</li>
                            <li>Comprovante de residência no município de Sumaré com menos de 60 dias de emissão, em nome
                                dos pais ou
                                do responsável legal pelo (a) candidato (a); (Original e 01 cópia)</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo e(route('user.call.pdf')); ?>" class="btn btn-outline-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dash.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/dashboard/perfil.blade.php ENDPATH**/ ?>