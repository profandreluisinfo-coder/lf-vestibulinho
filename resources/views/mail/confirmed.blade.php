<p>Prezado(a) Candidato(a):</p>
<p><strong>Seu endereço de e-mail foi confirmado com sucesso!</strong></p>
<p>Próximo passo: clique <a href="{{ route('login') }}" target="_blank">AQUI</a> para acessar a Área do Candidato e preencher o formulário de inscrição.</p>
<p>Desejamos boa sorte no <strong>{{ config('app.name') }} {{ $calendar->year }}</strong>.</p>
<p>Até breve,</p>
<p><strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong></p>
@include("mail.footer")