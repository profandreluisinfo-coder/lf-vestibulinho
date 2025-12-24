<section id="courses">

    <div class="container">
        
        <h3 class="simple-title">Cursos Técnicos</h3>

        <div class="row g-4">

            @foreach ($courses as $course)

            <div class="col-lg-6">

                <div class="course-card">
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-briefcase text-primary me-3" style="font-size: 2rem;"></i>
                        <h4 class="mb-0">{{ $course->name }}</h4>
                    </div>
                    
                    <p>{{ $course->info }}</p>
                    
                        <div class="d-flex justify-content-between align-items-center">
                        @if ($course->vacancies)
                            <span class="badge bg-primary">{{ $course->vacancies }} vagas</span>
                        @endif
                        <small class="text-muted">Duração: {{ $course->duration }} anos</small>
                    </div>
                
                </div>
            
            </div>

            @endforeach

        </div>

    </div>

</section>