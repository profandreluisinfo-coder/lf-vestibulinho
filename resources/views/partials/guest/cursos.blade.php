    <section id="cursos">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Oferta Acadêmica</div>
                <h2 class="section-title mb-3">Escolha seu <span>Curso Técnico</span></h2>
                <p class="section-lead mx-auto text-center">
                    Todos os cursos são gratuitos, presenciais e emitem certificado de técnico. Escolha sua área e
                    construa sua carreira.
                </p>
            </div>

            <div class="row g-4">
                @foreach ($courses as $course)
                    <div class="col-sm-6 col-lg-3 reveal delay-{{ $course->delay }}">
                        <div class="course-card {{ $course->card }}">
                            <div class="icon-wrap"><i class="bi bi-{{ $course->icone }}"></i></div>
                            <h3>{{ $course->name }}</h3>
                            <p>{{ $course->info }}</p>
                            <span class="tag-vagas">
                                <i class="bi bi-people-fill me-1"></i>{{ $course->vacancies ?? '' }} Vagas disponíveis
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>