<p>Prezado(a) candidato(a), {{ $name }}!</p>
<p>Informamos que sua autorização para uso de nome social/afetivo foi <strong>indeferida</strong>.</p>

@if ($observations)
<p>O indeferimento foi motivado por: <strong>{{ $observations }}</strong></p>
@endif

<p>Ressaltamos que o indeferimento <strong>não</strong> prejudica sua participação no Processo Seletivo Público.</p>
<p>Atenciosamente,</p>
<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("partials.emails.footer")