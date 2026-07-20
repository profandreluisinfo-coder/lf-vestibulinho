<p>Prezado(a) candidato(a), {{ $name }}!</p>
<p>Informamos que o seu relatório/laudo foi <strong>indeferido</strong>.</p>

@if ($observations)
<p>O indeferimento foi motivado por: <strong>{{ $observations }}</strong></p>
@endif

{{-- <p>No entanto, você pode solicitar uma nova avaliação entregando um novo documento presencialmente na secretaria da escola, das 18h30 às 22h30.</p> --}}

<p>Ressaltamos que o indeferimento <strong>não</strong> prejudica sua participação no Processo Seletivo Público. No entanto, sua inscrição permanecerá na modalidade de Ampla Concorrência (AC).</p>
<p>Atenciosamente,</p>
<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("partials.emails.footer")