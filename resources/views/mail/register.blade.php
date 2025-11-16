<p>Olá {{ $name }},</p>

<p><strong>Parabéns! Sua inscrição no Processo Seletivo {{ $calendar->year }} foi concluída com sucesso.</strong></p>
<p>Em anexo, você encontrará uma cópia da sua ficha de inscrição para conferência.</p>
<p><strong>Sugerimos que você a imprima e apresente no dia da prova.</strong></p>
<p>Qualquer dúvida, estamos à sua disposição. Ela também está disponível para download e impressão na <a href="{{ route('login') }}" target="_blank">Área do Candidato.</p>

<p>Atenciosamente,</p>

<p><strong>Escola Municipal Dr. Leandro Franceschini<br>Prefeitura Municipal de Sumaré</strong></p>

@include("mail.footer")