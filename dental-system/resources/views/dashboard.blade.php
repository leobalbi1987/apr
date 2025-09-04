@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Bem-vindo, ' . Auth::user()->firstname . '!')

@section('page-actions')
<a href="{{ route('schedules.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>
    Novo Agendamento
</a>
@endsection

@section('content')
<div class="row">
    <!-- Estatísticas Rápidas -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Agendamentos</h6>
                        <h3 class="mb-0">{{ $totalSchedules }}</h3>
                        <small>Total de agendamentos</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-check" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Confirmados</h6>
                        <h3 class="mb-0">{{ $confirmedSchedules }}</h3>
                        <small>Agendamentos confirmados</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pendentes</h6>
                        <h3 class="mb-0">{{ $pendingSchedules }}</h3>
                        <small>Aguardando confirmação</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Próximo</h6>
                        <h3 class="mb-0">{{ $nextSchedule ? $nextSchedule->schedule_date->format('d/m') : '--' }}</h3>
                        <small>Próximo agendamento</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-event" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Próximos Agendamentos -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Próximos Agendamentos
                </h5>
                <a href="{{ route('my-schedules') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body">
                @if($upcomingSchedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Horário</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingSchedules as $schedule)
                                <tr>
                                    <td>
                                        <strong>{{ $schedule->schedule_date->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ $schedule->schedule_date->format('l') }}</small>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $schedule->schedule_time->format('H:i') }}
                                    </td>
                                    <td>
                                        <strong>{{ $schedule->service->service_name }}</strong><br>
                                        <small class="text-muted">R$ {{ number_format($schedule->service->price, 2, ',', '.') }}</small>
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
                                        @if($schedule->status === 'pending')
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="cancelSchedule({{ $schedule->schedule_id }})">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h6 class="mt-3 text-muted">Nenhum agendamento próximo</h6>
                        <p class="text-muted">Que tal agendar uma consulta?</p>
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Agendar Consulta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informações do Perfil -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Meu Perfil
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mt-3 mb-1">{{ Auth::user()->full_name }}</h5>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="mb-1">{{ $totalSchedules }}</h6>
                        <small class="text-muted">Agendamentos</small>
                    </div>
                    <div class="col-6">
                        <h6 class="mb-1">{{ Auth::user()->created_at->diffInDays() }}</h6>
                        <small class="text-muted">Dias conosco</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-2"></i>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Serviços Disponíveis -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Serviços Disponíveis
                </h5>
            </div>
            <div class="card-body">
                @foreach($services as $service)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $service->service_name }}</strong><br>
                        <small class="text-muted">{{ $service->description }}</small>
                    </div>
                    <div class="text-end">
                        <strong class="text-primary">R$ {{ number_format($service->price, 2, ',', '.') }}</strong>
                    </div>
                </div>
                @if(!$loop->last)
                    <hr class="my-2">
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelSchedule(scheduleId) {
    if (confirm('Tem certeza que deseja cancelar este agendamento?')) {
        // Implementar cancelamento via AJAX
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