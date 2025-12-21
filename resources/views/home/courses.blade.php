@php
    $vacancies = \App\Models\Course::getVacancies();
@endphp


    <section id="courses">

        <div class="container">
            <h3 class="simple-title">Cursos</h3>
            <div class="row g-4">

                <div class="col-lg-6">
                    <div class="course-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-briefcase text-primary me-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0">Técnico em Administração</h4>
                        </div>
                        <p>Forme-se para atuar em empresas de diversos segmentos, desenvolvendo competências em
                            gestão, planejamento e organização empresarial.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            @if ($vacancies)
                            <span class="badge bg-primary">{{ $courses[0]->vacancies ?? 0 }} vagas</span>
                            @endif
                            <small class="text-muted">Duração: 4 anos</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="course-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-calculator text-primary me-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0">Técnico em Contabilidade</h4>
                        </div>
                        <p>Capacite-se para trabalhar com controle financeiro, análise contábil e assessoria
                            fiscal em empresas e escritórios contábeis.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $courses[1]->vacancies ?? 0 }} vagas</span>
                            <small class="text-muted">Duração: 4 anos</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="course-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-laptop text-primary me-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0">Técnico em Informática</h4>
                        </div>
                        <p>Desenvolva habilidades em programação, manutenção de computadores, redes e suporte
                            técnico para o mercado de TI.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $courses[2]->vacancies ?? 0 }} vagas</span>
                            <small class="text-muted">Duração: 4 anos</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="course-card">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-shield-check text-primary me-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0">Técnico em Segurança do Trabalho</h4>
                        </div>
                        <p>Torne-se especialista em prevenção de acidentes e promoção da saúde e segurança no
                            ambiente de trabalho.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $courses[3]->vacancies ?? 0 }} vagas</span>
                            <small class="text-muted">Duração: 4 anos</small>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>