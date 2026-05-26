    <section id="calendario">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Datas Importantes</div>
                <h2 class="section-title mb-3">Calendário do <span>Processo Seletivo</span></h2>
                <p class="section-lead mx-auto text-center">Fique atento a todas as datas. Recomendamos salvar os prazos com antecedência.</p>
            </div>

            <div class="row g-3 justify-content-center">
                <div class="col-lg-8">
                    <div class="cal-card mb-3 reveal delay-1">
                        <div class="cal-date">
                            <div class="day">{{ $calendar->inscription_start?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->inscription_start?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Início das Inscrições</h5>
                            <p>Abertura do portal de inscrições online — acesso pelo site oficial.</p>
                        </div>
                        <span class="cal-badge badge-open">Abertura</span>
                    </div>
                    <div class="cal-card mb-3 reveal delay-2">
                        <div class="cal-date" style="background:var(--teal2);">
                            <div class="day">{{ $calendar->inscription_end?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->inscription_end?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Encerramento das Inscrições</h5>
                            <p>Último dia para realizar a inscrição. Não haverá prorrogação.</p>
                        </div>
                        <span class="cal-badge badge-close">Prazo</span>
                    </div>
                    <div class="cal-card mb-3 reveal delay-3">
                        <div class="cal-date" style="background:#7B3FA0;">
                            <div class="day">{{ $calendar->exam_location_publish?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->exam_location_publish?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Divulgação dos Locais de Prova</h5>
                            <p>Local e horário de prova disponíveis na Área do Candidato.</p>
                        </div>
                        <span class="cal-badge badge-event">Evento</span>
                    </div>
                    <div class="cal-card mb-3 reveal delay-2">
                        <div class="cal-date" style="background:#C0392B;">
                            <div class="day">{{ $calendar->exam_date?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->exam_date?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Dia da Prova</h5>
                            <p>Realização da prova escrita. Levar RG original. Portões fecham às 8h.</p>
                        </div>
                        <span class="cal-badge badge-event">Prova</span>
                    </div>
                    <div class="cal-card mb-3 reveal delay-3">
                        <div class="cal-date" style="background:var(--amber2);">
                            <div class="day">{{ $calendar->final_result_publish?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->final_result_publish?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Divulgação da Classificação</h5>
                            <p>Lista de classificados publicada no site e na Área do Candidato.</p>
                        </div>
                        <span class="cal-badge" style="background:rgba(224,122,58,.15);color:var(--amber2);">Resultado</span>
                    </div>
                    <div class="cal-card reveal delay-4">
                        <div class="cal-date" style="background:var(--teal);">
                            <div class="day">{{ $calendar->enrollment_start?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->enrollment_start?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Convocação e Matrícula</h5>
                            <p>Candidatos convocados devem realizar a matrícula presencialmente.</p>
                        </div>
                        <span class="cal-badge badge-open">Matrícula</span>
                    </div>
                    <div class="text-center mt-4 reveal delay-4">
                        <a href="{{ route('guest.calendar.show') }}" class="btn-faq-more">
                            Ver todas as datas do Vestibulinho <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>