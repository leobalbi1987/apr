@extends('layouts.app')

@section('title', 'Meus Agendamentos')
@section('page-title', 'Meus Agendamentos')

@section('page-actions')
<a href="{{ route('schedules.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>
    Novo Agendamento
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('my-schedules') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Data Inicial</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Data Final</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('my-schedules') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Limpar
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
                    Histórico de Agendamentos
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
                                    <th>Serviço</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th>Observações</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->schedule_date->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $schedule->schedule_time->format('H:i') }}
                                            </small><br>
                                            <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->service->service_name }}</strong><br>
                                            <small class="text-muted">{{ $schedule->service->description }}</small>
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
                                        <br>
                                        <small class="text-muted">
                                            {{ $schedule->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            R$ {{ number_format($schedule->service->price, 2, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($schedule->notes)
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $schedule->notes }}">
                                                {{ $schedule->notes }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="showDetails({{ $schedule->schedule_id }})" 
                                                    title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($schedule->status === 'pending' && $schedule->schedule_date->isFuture())
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
                        <p class="text-muted">Você ainda não possui agendamentos ou nenhum corresponde aos filtros aplicados.</p>
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Fazer Primeiro Agendamento
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event me-2"></i>
                    Detalhes do Agendamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showDetails(scheduleId) {
    // Buscar detalhes do agendamento via AJAX
    fetch(`/schedules/${scheduleId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const schedule = data.schedule;
                const modalContent = document.getElementById('modalContent');
                
                modalContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="bi bi-calendar me-2"></i>Data e Horário</h6>
                            <p><strong>${schedule.formatted_date}</strong> às <strong>${schedule.formatted_time}</strong></p>
                            
                            <h6><i class="bi bi-gear me-2"></i>Serviço</h6>
                            <p><strong>${schedule.service_name}</strong><br>
                            <small class="text-muted">${schedule.service_description}</small></p>
                            
                            <h6><i class="bi bi-cash me-2"></i>Valor</h6>
                            <p class="text-success"><strong>R$ ${schedule.formatted_price}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="bi bi-info-circle me-2"></i>Status</h6>
                            <p><span class="badge bg-${schedule.status_color}">${schedule.status_text}</span></p>
                            
                            <h6><i class="bi bi-clock-history me-2"></i>Criado em</h6>
                            <p>${schedule.created_at}</p>
                            
                            ${schedule.notes ? `
                                <h6><i class="bi bi-chat-text me-2"></i>Observações</h6>
                                <p>${schedule.notes}</p>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                new bootstrap.Modal(document.getElementById('detailsModal')).show();
            } else {
                alert('Erro ao carregar detalhes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao carregar detalhes');
        });
}

function cancelSchedule(scheduleId) {
    if (confirm('Tem certeza que deseja cancelar este agendamento?')) {
        fetch(`/schedules/${scheduleId}/cancel`, {
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
                alert('Erro ao cancelar agendamento');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao cancelar agendamento');
        });
    }
}
</script>
@endpush