<div class="accordion accordion-flush shadow-lg mt-5" id="accordionFlushTwo">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                📊 Estatísticas
            </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushTwo">
            <div class="accordion-body">
                <div class="row g-3 mb-4">

                    {{-- Cards e Modais permanecem exatamente como estão --}}
                    {{-- Card 1: Inscritos por Curso --}}
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#modalCursos">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-bar-chart-fill fs-2 text-primary mb-2"></i>
                                <p class="mb-0 fw-semibold">Inscritos por Curso</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Inscritos por Sexo --}}
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#modalSexos">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-pie-chart-fill fs-2 text-success mb-2"></i>
                                <p class="mb-0 fw-semibold">Inscritos por Sexo</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Inscritos por Curso e Sexo --}}
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#modalSexoPorCurso">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-bar-chart-steps fs-2 text-warning mb-2"></i>
                                <p class="mb-0 fw-semibold">Inscritos por Curso e Sexo</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Bairros --}}
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#modalBairros">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-geo-alt-fill fs-2 text-danger mb-2"></i>
                                <p class="mb-0 fw-semibold">Bairros com Mais Candidatos</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 5: Escolas --}}
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                            data-bs-target="#modalEscolas">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-building fs-2 text-secondary mb-2"></i>
                                <p class="mb-0 fw-semibold">Escolas de Origem</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Modais individuais --}}
                {{-- Modal: Cursos --}}
                <div class="modal fade" id="modalCursos" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Inscritos por Curso</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <canvas id="chartCursos" data-labels='@json($cursos->pluck('curso'))'
                                    data-values='@json($cursos->pluck('total'))'></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal: Sexos --}}
                <div class="modal fade" id="modalSexos" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Inscritos por Sexo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <canvas id="chartSexos" data-labels='@json($sexos->pluck('gender'))'
                                    data-values='@json($sexos->pluck('total'))'></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal: Sexo por Curso --}}
                <div class="modal fade" id="modalSexoPorCurso" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Inscritos por Curso e Sexo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <canvas id="chartSexoPorCurso" data-cursos='@json($sexoPorCurso->pluck('course'))'
                                    data-masculino='@json($sexoPorCurso->pluck('masculino'))'
                                    data-feminino='@json($sexoPorCurso->pluck('feminino'))'></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal: Bairros --}}
                <div class="modal fade" id="modalBairros" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">10 Bairros com Mais Candidatos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <canvas id="chartBairros" data-labels='@json($bairros->pluck('burgh'))'
                                    data-values='@json($bairros->pluck('total'))'></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal: Escolas --}}
                <div class="modal fade" id="modalEscolas" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">10 Escolas de Origem com Mais Candidatos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <canvas id="chartEscolas" data-labels='@json($escolas->pluck('school_name'))'
                                    data-values='@json($escolas->pluck('total'))'></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
