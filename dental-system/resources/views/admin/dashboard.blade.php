@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrativo')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>
        Novo Membro
    </a>
    <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary">
        <i class="bi bi-gear-fill me-2"></i>
        Novo Serviço
    </a>
</div>
@endsection

@section('content')
<!-- Estatísticas Principais -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total de Membros</h6>
                        <h3 class="mb-0">{{ $stats['total_members'] }}</h3>
                        <small>Usuários registrados</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Agendamentos</h6>
                        <h3 class="mb-0">{{ $stats['total_schedules'] }}</h3>
                        <small>Total de agendamentos</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pendentes</h6>
                        <h3 class="mb-0">{{ $stats['pending_schedules'] }}</h3>
                        <small>Aguardando confirmação</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Hoje</h6>
                        <h3 class="mb-0">{{ $stats['today_schedules'] }}</h3>
                        <small>Agendamentos de hoje</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-event" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Agendamentos Recentes -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Agendamentos Recentes
                </h5>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body">
                @if($recentSchedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Membro</th>
                                    <th>Data/Hora</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSchedules as $schedule)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-person-fill text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $schedule->member->full_name }}</strong><br>
                                                <small class="text-muted">{{ $schedule->member->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $schedule->schedule_date->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $schedule->schedule_time->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $schedule->service->service_name }}</strong><br>
                                        <small class="text-success">R$ {{ number_format($schedule->service->price, 2, ',', '.') }}</small>
                                    </td>
                                    <td>
                                        @if($schedule->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Pendente
                                            </span>
                                        @elseif($schedule->status === 'confirmed')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Confirmado
                                            </span>
                                        @elseif($schedule->status === 'completed')
                                            <span class="badge bg-primary">
                                                <i class="bi bi-check-all me-1"></i>Concluído
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Cancelado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.schedules.show', $schedule->schedule_id) }}" 
                                               class="btn btn-outline-primary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($schedule->status === 'pending')
                                                <button class="btn btn-outline-success" 
                                                        onclick="updateStatus({{ $schedule->schedule_id }}, 'confirmed')" 
                                                        title="Confirmar">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h6 class="mt-3 text-muted">Nenhum agendamento recente</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Painel Lateral -->
    <div class="col-lg-4">
        <!-- Próximos Agendamentos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    Próximos Agendamentos
                </h5>
            </div>
            <div class="card-body">
                @if($upcomingSchedules->count() > 0)
                    @foreach($upcomingSchedules as $schedule)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>{{ $schedule->member->firstname }}</strong><br>
                            <small class="text-muted">
                                {{ $schedule->schedule_date->format('d/m') }} às {{ $schedule->schedule_time->format('H:i') }}
                            </small><br>
                            <small class="text-primary">{{ $schedule->service->service_name }}</small>
                        </div>
                        <div>
                            @if($schedule->status === 'pending')
                                <span class="badge bg-warning">Pendente</span>
                            @else
                                <span class="badge bg-success">Confirmado</span>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="my-2">
                    @endif
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x" style="font-size: 2rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-2 mb-0">Nenhum agendamento próximo</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.members.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>
                        Adicionar Membro
                    </a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-gear-fill me-2"></i>
                        Novo Serviço
                    </a>
                    <a href="{{ route('admin.notes.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-sticky me-2"></i>
                        Adicionar Nota
                    </a>
                    <a href="{{ route('admin.schedules.index') }}?status=pending" class="btn btn-outline-warning">
                        <i class="bi bi-clock me-2"></i>
                        Ver Pendentes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(scheduleId, status) {
    if (confirm(`Tem certeza que deseja ${status === 'confirmed' ? 'confirmar' : 'alterar'} este agendamento?`)) {
        fetch(`/admin/schedules/${scheduleId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao atualizar status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao atualizar status');
        });
    }
}
</script>
@endpush