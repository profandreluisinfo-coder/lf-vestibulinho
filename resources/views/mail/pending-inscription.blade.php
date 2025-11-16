<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Finalize sua inscrição</title>
</head>
<body>
    <p>Prezado(a) {{ $name }},</p>

    <p>Verificamos que até o momento você ainda não concluiu sua inscrição no Vestibulinho.</p>

    <p>Para participar, acesse sua <a href="{{ $link }}">Área do Candidato</a> e preencha a ficha de inscrição.</p>

    <p>Atenciosamente,<br>
    Comissão Organizadora do Vestibulinho</p>
    @include("mail.footer")
</body>
</html>