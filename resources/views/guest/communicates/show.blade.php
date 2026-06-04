@extends('layouts.guest')

@section('title', $communicate->titulo . ' — Vestibulinho ' . ($calendar?->year ?? config('app.year')))

@push('styles')
	<link rel="stylesheet" href="{{ asset('assets/css/guest/communicates.css') }}" />
@endpush

@section('content')

	<section class="section-communicates py-5">
		<div class="container">
			<div class="row mb-4">
				<div class="col-12 reveal">
					<div class="fa-header-accent">
						<i class="bi bi-megaphone-fill"></i>
						Comunicado
					</div>

					<h2 class="section-title">{{ $communicate->titulo }}</h2>

					<div class="comunicado-meta mb-3">
						<span class="comunicado-data">
							<i class="bi bi-calendar3 me-1"></i>
							{{ $communicate->published_at?->format('d/m/Y H:i') ?? $communicate->created_at->format('d/m/Y H:i') }}
						</span>
						<span class="comunicado-badge badge-{{ $communicate->tipo ?? 'info' }} ms-2">
							{{ ucfirst($communicate->tipo ?? 'aviso') }}
						</span>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-lg-8 mx-auto reveal">

					<div class="card p-4">
						<div class="card-body">
							@if (!empty($communicate->resumo))
								<div class="comunicado-resumo mb-3">{!! $communicate->resumo !!}</div>
							@endif

							@if ($communicate->attachments->isNotEmpty())
								<div class="mt-4">
									<h6>Anexos</h6>
									<ul class="list-unstyled">
										@foreach ($communicate->attachments as $attachment)
											<li>
												<a href="{{ Storage::url($attachment->path) }}" target="_blank" rel="noopener">
													<i class="bi bi-paperclip"></i> {{ $attachment->name }}
												</a>
											</li>
										@endforeach
									</ul>
								</div>
							@endif

							<div class="mt-4 d-flex justify-content-between align-items-center">
								<a href="{{ route('guest.communicates.index') }}" class="btn btn-outline-secondary">&larr; Voltar</a>
								@if (!empty($communicate->url))
									<a href="{!! $communicate->url !!}" class="btn btn-primary" target="_blank" rel="noopener">Abrir link</a>
								@endif
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>

@endsection

