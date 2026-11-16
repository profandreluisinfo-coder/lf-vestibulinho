{{-- resources/views/admin/reports/partials/status-badge.blade.php --}}
@switch($status ?? null)
    @case('pending')
        <span class="badge bg-warning text-dark">
            <i class="bi bi-hourglass-split me-1"></i> Pendente
        </span>
    @break

    @case('accepted')
        <span class="badge bg-success">
            <i class="bi bi-check-circle-fill me-1"></i> Deferido
        </span>
    @break

    @case('rejected')
        <span class="badge bg-danger">
            <i class="bi bi-x-circle-fill me-1"></i> Indeferido
        </span>
    @break

    @default
        —
@endswitch