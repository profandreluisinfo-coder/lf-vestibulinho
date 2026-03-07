<section id="guidelines">
    <div class="container">        
        <h3 class="simple-title">Orientações</h3>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="course-card">                                        
                    <p>Para participar do <strong>{{ config('app.name') }} {{ $calendar?->year }}</strong>, é necessário cadastrar e validar seus dados de acesso. O candidato deverá acessar e preencher o <a href="{{ route('register') }}" class="text-decoration-none">formulário de registro</a> e, em seguida, verificar seu e-mail. Será enviado um <i>link</i> de confirmação para o endereço de e-mail informado. Clique nesse <i>link</i> para validar o endereço de e-mail informado. Sem essa confirmação, não será possível participar do Vestibulinho.</p>                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('register') }}" class="btn btn-primary">link</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>