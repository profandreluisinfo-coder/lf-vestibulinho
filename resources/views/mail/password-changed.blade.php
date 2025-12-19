<p>Prezado(a) Candidato(a): @if($name)<strong>{{ $name }}</strong> @endif</p>
<p>Conforme sua solicitação, <strong>sua senha foi alterada com sucesso!</strong></p>
<p>Desejamos boa sorte no <strong>{{ config('app.name') }} {{ $calendar->year }}</strong>.</p>
<p>Até breve,</p>
<p><strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong></p>
@include("partials.mail.footer")