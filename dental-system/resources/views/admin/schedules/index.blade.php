@extends('layouts.app')

@section('title', 'Gerenciar Agendamentos')
@section('page-title', 'Gerenciar Agendamentos')

@section('page-actions')
<a href="#" class="btn btn-primary disabled" title="Create functionality not implemented">
    <i class="bi bi-calendar-plus me-2"></i>
    Novo Agendamento
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                <p class="mb-0">Total de Agendamentos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                <p class="mb-0">Pendentes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['confirmed'] }}</h4>
                                <p class="mb-0">Confirmados</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['today'] }}</h4>
                                <p class="mb-0">Hoje</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Nome do membro" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="service" class="form-label">Serviço</label>
                        <select name="service" id="service" class="form-select">
                            <option value="">Todos os serviços</option>
                            @foreach($services as $service)
                                <option value="{{ $service->service_id }}" {{ request('service') == $service->service_id ? 'selected' : '' }}>
                                    {{ $service->service_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Data de</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Data até</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Agendamentos -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Lista de Agendamentos
                </h5>
                <span class="badge bg-primary">{{ $schedules->total() }} agendamentos</span>
            </div>
            <div class="card-body">
                @if($schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Membro</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr class="{{ $schedule->schedule_date->isToday() ? 'table-warning' : '' }}">
                                    <td>
                                        <div>
                                            <strong class="{{ $schedule->schedule_date->isPast() && $schedule->status !== 'completed' ? 'text-danger' : '' }}">
                                                {{ $schedule->schedule_date->format('d/m/Y') }}
                                            </strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $schedule->schedule_time->format('H:i') }}
                                            </small><br>
                                            <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                            @if($schedule->schedule_date->isToday())
                                                <br><span class="badge bg-info">Hoje</span>
                                            @elseif($schedule->schedule_date->isTomorrow())
                                                <br><span class="badge bg-warning">Amanhã</span>
                                            @elseif($schedule->schedule_date->isPast() && $schedule->status !== 'completed')
                                                <br><span class="badge bg-danger">Atrasado</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ strtoupper(substr($schedule->member->first_name, 0, 1) . substr($schedule->member->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $schedule->member->full_name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    {{ $schedule->member->email }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>
                                                    {{ $schedule->member->phone }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->service->service_name }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($schedule->service->description, 40) }}</small>
                                        </div>
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
                                        <strong class="text-success">
                                            R$ {{ number_format($schedule->service->price, 2, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">
                                                {{ $schedule->created_at->format('d/m/Y') }}<br>
                                                {{ $schedule->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.schedules.show', $schedule->schedule_id) }}" 
                                               class="btn btn-outline-primary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($schedule->status === 'pending')
                                                <button class="btn btn-outline-success" 
                                                        onclick="confirmSchedule({{ $schedule->schedule_id }})" 
                                                        title="Confirmar">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            @if($schedule->status === 'confirmed')
                                                <button class="btn btn-outline-primary" 
                                                        onclick="completeSchedule({{ $schedule->schedule_id }})" 
                                                        title="Marcar como concluído">
                                                    <i class="bi bi-check-all"></i>
                                                </button>
                                            @endif
                                            @if(in_array($schedule->status, ['pending', 'confirmed']))
                                                <button class="btn btn-outline-danger" 
                                                        onclick="cancelSchedule({{ $schedule->schedule_id }})" 
                                                        title="Cancelar">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $schedules->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 4rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">Nenhum agendamento encontrado</h5>
                        <p class="text-muted">Não há agendamentos ou nenhum corresponde aos filtros aplicados.</p>
                        <a href="#" class="btn btn-primary disabled" title="Create functionality not implemented">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Criar Primeiro Agendamento
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
function confirmSchedule(scheduleId) {
    if (confirm('Confirmar este agendamento?')) {
        updateScheduleStatus(scheduleId, 'confirmed');
    }
}

function completeSchedule(scheduleId) {
    if (confirm('Marcar este agendamento como concluído?')) {
        updateScheduleStatus(scheduleId, 'completed');
    }
}

function cancelSchedule(scheduleId) {
    if (confirm('Tem certeza que deseja cancelar este agendamento?')) {
        updateScheduleStatus(scheduleId, 'cancelled');
    }
}

function updateScheduleStatus(scheduleId, status) {
    fetch(`/admin/schedules/${scheduleId}/status`, {
        method: 'POST',
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
            alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao atualizar status');
    });
}
</script>
@endpush