@extends('layouts.app')

@section('title', 'Detalhes do Serviço')
@section('page-title', 'Detalhes do Serviço')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Voltar
    </a>
    <a href="{{ route('admin.services.edit', $service->service_id) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>
        Editar
    </a>
    <button class="btn btn-danger" 
            onclick="deleteService({{ $service->service_id }})">
        <i class="bi bi-trash me-2"></i>
        Excluir Serviço
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Informações do Serviço -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Informações do Serviço
                </h5>
                <span class="badge bg-success fs-6">
                    <i class="bi bi-check-circle me-1"></i>Ativo
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Nome do Serviço</h6>
                        <h4 class="mb-4">{{ $service->service_name }}</h4>
                        
                        <h6 class="text-muted mb-2">Preço</h6>
                        <h3 class="text-success mb-4">
                            R$ {{ number_format($service->price, 2, ',', '.') }}
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Data de Criação</h6>
                        <p class="mb-3">
                            {{ $service->created_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $service->created_at->diffForHumans() }})</small>
                        </p>
                        
                        <h6 class="text-muted mb-2">Última Atualização</h6>
                        <p class="mb-3">
                            {{ $service->updated_at->format('d/m/Y H:i') }}
                            <small class="text-muted">({{ $service->updated_at->diffForHumans() }})</small>
                        </p>
                    </div>
                </div>
                
                @if($service->description)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Descrição</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $service->description }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Histórico de Agendamentos -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Histórico de Agendamentos
                </h5>
                <span class="badge bg-primary">{{ $schedules->total() }} agendamentos</span>
            </div>
            <div class="card-body">
                @if($schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Membro</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->schedule_date->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $schedule->schedule_time }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->member->first_name }} {{ $schedule->member->last_name }}</strong><br>
                                            <small class="text-muted">{{ $schedule->member->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($schedule->status)
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Pendente
                                                </span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Confirmado
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-check-all me-1"></i>Concluído
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Cancelado
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            R$ {{ number_format($schedule->total_amount, 2, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.schedules.show', $schedule->schedule_id) }}" 
                                               class="btn btn-outline-primary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($schedule->status === 'pending')
                                                <button class="btn btn-outline-success" 
                                                        onclick="updateScheduleStatus({{ $schedule->schedule_id }}, 'confirmed')" 
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
                    
                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $schedules->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h6 class="mt-3 text-muted">Nenhum agendamento encontrado</h6>
                        <p class="text-muted">Este serviço ainda não possui agendamentos.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estatísticas e Ações -->
    <div class="col-lg-4">
        <!-- Estatísticas Rápidas -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Estatísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $stats['total_schedules'] }}</h4>
                            <small class="text-muted">Total de Agendamentos</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success mb-0">{{ $stats['confirmed_schedules'] }}</h4>
                        <small class="text-muted">Confirmados</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $stats['pending_schedules'] }}</h4>
                            <small class="text-muted">Pendentes</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-info mb-0">{{ $stats['completed_schedules'] }}</h4>
                        <small class="text-muted">Concluídos</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <h5 class="text-success mb-1">
                        R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}
                    </h5>
                    <small class="text-muted">Receita Total Gerada</small>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.services.edit', $service->service_id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>
                        Editar Serviço
                    </a>
                    
                    <a href="{{ route('admin.services.edit', $service->service_id) }}" 
                       class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>
                        Editar Serviço
                    </a>
                    
                    <a href="{{ route('admin.schedules.index', ['service' => $service->service_id]) }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>
                        Ver Todos os Agendamentos
                    </a>
                    
                    <hr>
                    
                    <button class="btn btn-outline-danger" onclick="deleteService({{ $service->service_id }})">
                        <i class="bi bi-trash me-2"></i>
                        Excluir Serviço
                    </button>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informações Adicionais
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">ID do Serviço</small>
                    <code>{{ $service->service_id }}</code>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Criado em</small>
                    <span>{{ $service->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Atualizado em</small>
                    <span>{{ $service->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
                
                <div class="mb-0">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge bg-success">
                        Ativo
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleServiceStatus(serviceId) {
    const confirmMessage = 'Tem certeza que deseja alterar o status deste serviço?';
    
    if (confirm(confirmMessage)) {
        fetch(`/admin/services/${serviceId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao alterar status');
        });
    }
}

function deleteService(serviceId) {
    const confirmMessage = 'Tem certeza que deseja excluir este serviço?\n\nEsta ação não pode ser desfeita e afetará todos os agendamentos relacionados.';
    
    if (confirm(confirmMessage)) {
        fetch(`/admin/services/${serviceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/admin/services';
            } else {
                alert('Erro ao excluir serviço: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir serviço');
        });
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