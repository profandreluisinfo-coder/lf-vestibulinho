<p>Olá {{ $name }},</p>

<p><strong>Parabéns! Sua inscrição no Processo Seletivo {{ $process?->year }} foi concluída com sucesso.</strong></p>
<p>Em anexo, você encontrará uma cópia do seu protocolo de inscrição.</p>
<p><strong>Sugerimos que você o imprima e apresente no dia da prova.</strong></p>
<p>Ele também está disponível para <i>download</i> e impressão na <a href="{{ route('login') }}" target="_blank">Área do Candidato.</p>

<p>Atenciosamente,</p>

<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("partials.emails.footer")