@extends('layouts.site')

@section('title', 'EM Dr Leandro Franceschini')

@section('content')

<!-- ===== HERO SECTION REVISADO ===== -->
<section class="hero">
    <div class="hero-circle hero-circle-1"></div>
    <div class="hero-circle hero-circle-2"></div>
    <div class="hero-circle hero-circle-3"></div>

    <div class="container-lg">
        <div class="hero-content">
            <span class="hero-pretitle">Bem-vindo!</span>
            
            <h1 class="hero-title">
                Formando <span class="accent">Futuro</span> com <span class="accent">Excelência</span>
            </h1>
            
            <p class="hero-description">
                A EM Dr Leandro Franceschini oferece educação de qualidade, 
                preparando adolescentes para um futuro brilhante 
                com valores humanistas e aprendizado significativo.
            </p>
            
            <div class="hero-cta-group">
                <a href="#sobre" class="btn-hero-primary">
                    Conhecer a Escola <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <a href="/noticias" class="btn-hero-outline">
                    <i class="bi bi-newspaper me-2"></i> Últimas Notícias
                </a>
            </div>
            
            <!-- Stats -->
            <div class="hero-stats">
                <div class="hero-stat-item">
                    <div class="hero-stat-number">60+</div>
                    <div class="hero-stat-label">Anos de História</div>
                </div>
                <div class="hero-stat-item">
                    <div class="hero-stat-number">1000+</div>
                    <div class="hero-stat-label">Alunos</div>
                </div>
                <div class="hero-stat-item">
                    <div class="hero-stat-number">70+</div>
                    <div class="hero-stat-label">Professores</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Scroll hint --}}
    <div class="scroll-hint">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <span>rolar</span>
    </div>

</section>

<!-- ===== SEÇÃO SOBRE REVISADA ===== -->
<section class="about-section" id="sobre">
    <div class="container-lg">
        <div class="about-header">
            <div class="about-intro reveal">
                <div class="section-tag">
                    Sobre Nós
                </div>
                
                <h2 class="section-title">
                    Educação Integral e <span>Transformadora</span>
                </h2>
                
                <p class="section-lead">
                    Somos uma instituição comprometida com o desenvolvimento 
                    completo de nossos alunos, oferecendo metodologias inovadoras 
                    e um ambiente acolhedor.
                </p>
            </div>
        </div>

        <!-- Pilares da Escola -->
        <div class="about-pillars">
            <div class="pillar-card reveal">
                <span class="pillar-icon">📚</span>
                <h3 class="pillar-title">Excelência Acadêmica</h3>
                <p class="pillar-description">
                    Curriculum de qualidade com professores qualificados, 
                    metodologias ativas e resultados comprovados.
                </p>
            </div>

            <div class="pillar-card reveal delay-1">
                <span class="pillar-icon">🤝</span>
                <h3 class="pillar-title">Formação Humanista</h3>
                <p class="pillar-description">
                    Desenvolvemos valores éticos, empatia e responsabilidade 
                    social em nossos alunos.
                </p>
            </div>

            <div class="pillar-card reveal delay-2">
                <span class="pillar-icon">🚀</span>
                <h3 class="pillar-title">Inovação Constante</h3>
                <p class="pillar-description">
                    Tecnologia educacional, espaços modernos e programas que 
                    preparam para o futuro.
                </p>
            </div>
        </div>

        <!-- Mensagem Final da Seção About -->
        <div class="about-bottom reveal">
            <h3 class="about-bottom-title"><i class="bi bi-pin me-1"></i> Nosso Compromisso</h3>
            <p class="about-bottom-text">
                Garantir um ambiente seguro, acolhedor e estimulante onde cada 
                aluno desenvolva plenamente seus talentos, competências e valores, 
                tornando-se um cidadão responsável e preparado para os desafios do mundo.
            </p>
        </div>
    </div>
</section>

<!-- ===== SEÇÃO SOBRE CURSOS ===== -->
<section class="courses-section" id="cursos">
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== SEÇÃO NOTÍCIAS ===== -->
<section class="news-section" id="noticias">
    <div class="container-lg">
        <div class="section-header">
            <div class="section-tag">
                Atualizações
            </div>
            
            <h2 class="section-title">
                Últimas <span>Notícias</span>
            </h2>
            
            <p class="section-lead">
                Acompanhe os acontecimentos, eventos e informações importantes da escola.
            </p>
        </div>

        <div class="news-grid">
            @php 
            $i = 0;
            @endphp
            @foreach ($news as $new)
            <div class="reveal delay-{{ $i++ }}">
                <div class="news-card">
                    <div class="news-card-image news-card-image-teal">
                        📰
                    </div>
                    
                    <div class="news-card-body">
                        <span class="news-card-badge news-card-badge-teal">
                            Notícia
                        </span>
                        
                        <h3 class="news-card-title">
                            {{ $new->title }}
                        </h3>
                        
                        <p class="news-card-desc">
                            {{ $new->resume }}
                        </p>
                        
                        <div class="news-card-meta">
                            <span class="news-card-date">Há 2 dias</span>
                            <a href="{{ route('news.show', $new->slug) }}" class="news-card-link news-card-link-teal">
                                Ler mais →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA Notícias -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="/noticias" class="btn-hero-primary">
                Ver Todas as Notícias <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ===== SEÇÃO COMUNICADOS ===== -->
<section class="comunicados-section" id="comunicados">
    <div class="container-lg">
        <div class="section-header">
            <div class="section-tag">
                Avisos Oficiais
            </div>
            
            <h2 class="section-title">
                <span>Comunicados</span> Oficiais
            </h2>
            
            <p class="section-lead">
                Informações importantes, avisos e comunicações formais da escola.
            </p>
        </div>

        <div class="news-grid">
            @php 
            $i = 0;
            @endphp
            @foreach ($infos as $info)
            <div class="reveal delay-{{ $i++ }}">
                <div class="news-card">
                    <div class="news-card-image news-card-image-navy">
                        📢
                    </div>
                    
                    <div class="news-card-body">
                        <span class="news-card-badge news-card-badge-navy">
                            Comunicado Oficial
                        </span>
                        
                        <h3 class="news-card-title">
                            {{ $info->title }}
                        </h3>
                        
                        <p class="news-card-desc">
                            {{ $info->resume }}
                        </p>
                        
                        <div class="news-card-meta">
                            <span class="news-card-date">Há 1 dia</span>
                            <a href="{{ route('infos.show', $info->slug) }}" class="news-card-link news-card-link-navy">
                                Ler mais →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA Comunicados -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('infos.index') }}" class="btn-hero-primary">
                Ver Todos os Comunicados <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ===== SEÇÃO CTA FINAL ===== -->
<section class="cta-section">
    <div class="container-lg">
        <div class="cta-container">
            <h2 class="cta-title">
                Conheça Nossa Escola
            </h2>
            
            <p class="cta-description">
                Faça uma visita e conheça de perto a estrutura, ambiente e 
                a excelência educacional que oferecemos.
            </p>
            
            <div class="cta-buttons">
                <a href="tel:+551133334444" class="btn-hero-primary">
                    📞 Ligar para Escola
                </a>
                <a href="mailto:contato@leandrofranceschini.com.br" class="btn-hero-outline">
                    ✉️ Enviar Email
                </a>
            </div>
        </div>
    </div>
</section>

@endsection