<p>Prezado(a) candidato(a), {{ $name }}!</p>

@if ($appealType === 'pne')
<p>Informamos que o seu recurso contra o indeferimento do relatório/laudo foi <strong>deferido</strong> com sucesso.</p>
<p>Com isso, o seu relatório/laudo foi deferido e você está concorrendo às vagas destinadas aos candidatos portadores de necessidades especiais.</p>
@else
<p>Informamos que o seu recurso contra o indeferimento da autorização para uso de nome social/afetivo foi <strong>deferido</strong> com sucesso.</p>
<p>Com isso, a sua autorização foi deferida.</p>
@endif

<p>Atenciosamente,</p>
<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("partials.emails.footer")