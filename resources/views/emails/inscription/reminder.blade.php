<p>Olá, {{ $content['name'] ?? 'candidato(a)' }}!</p>

<p>
    Notamos que você confirmou seu e-mail no site do Vestibulinho LF {{ $process->year }},
    mas até o momento <strong>ainda não concluiu sua inscrição</strong>.
</p>

@if (!empty($content['end']))
    <p>
        As inscrições seguem abertas até
        <strong>{{ $content['end']->format('d/m/Y') }}</strong>.
    </p>
@endif

<p>
    Para finalizar, acesse o site e conclua todas as etapas:<br>
    <a href="{{ $content['url'] }}">{{ $content['url'] }}</a>
</p>

<p>Se você já se inscreveu ou não pretende participar, desconsidere esta mensagem.</p>

<p>Atenciosamente,<br>
    Equipe de Seleção E.M. Dr. Leandro Franceschini<br>
    <strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong>
</p>

@include('partials.emails.footer')