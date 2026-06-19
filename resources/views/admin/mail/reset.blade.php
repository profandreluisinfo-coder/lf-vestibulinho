<p>Prezado(a) Candidato(a), <strong>{{ $name }}</strong></p>
<p>Por favor, clique no <i>link</i> abaixo para iniciar o processo de redefinição de senha:</p>
<a href="{{ $link }}" target="_blank">{{ $link }}</a>
<p>Atenciosamente,</p>
<p><strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong></p>
@include("partials.mail.footer")