<p>Prezado(a) candidato(a), {{ $name }}!</p>

@if ($appealType === 'pne')
<p>Informamos que o seu recurso contra o indeferimento do relatório/laudo foi <strong>indeferido</strong>.</p>
@else
<p>Informamos que o seu recurso contra o indeferimento da autorização para uso de nome social/afetivo foi <strong>indeferido</strong>.</p>
@endif

@if ($observations)
<p>O indeferimento do recurso foi motivado por: <strong>{{ $observations }}</strong></p>
@endif

@if ($appealType === 'pne')
<p>Ressaltamos que o indeferimento <strong>não</strong> prejudica sua participação no Processo Seletivo Público. No entanto, sua inscrição permanecerá na modalidade de Ampla Concorrência (AC).</p>
@else
<p>Ressaltamos que o indeferimento <strong>não</strong> prejudica sua participação no Processo Seletivo Público.</p>
@endif

<p>Atenciosamente,</p>
<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("partials.emails.footer")