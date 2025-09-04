@extends('layouts.app')

@section('title', 'Detalhes do Membro')
@section('page-title', 'Detalhes do Membro')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.members.edit', $member->member_id) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>
        Editar
    </a>
    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        Voltar
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Informações do Membro -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Informações Pessoais
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                    </div>
                    <h4>{{ $member->full_name }}</h4>
                    <p class="text-muted">ID: {{ $member->member_id }}</p>
                    @if($member->email_verified_at)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Conta Verificada
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="bi bi-clock me-1"></i>Verificação Pendente
                        </span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Telefone</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:{{ $member->phone }}">{{ $member->phone }}</a>
                    </div>
                </div>

                @if($member->birth_date)
                <div class="mb-3">
                    <label class="form-label text-muted">Data de Nascimento</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        {{ $member->birth_date->format('d/m/Y') }}
                        <small class="text-muted ms-2">({{ $member->birth_date->age }} anos)</small>
                    </div>
                </div>
                @endif

                @if($member->address)
                <div class="mb-3">
                    <label class="form-label text-muted">Endereço</label>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span>{{ $member->address }}</span>
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-muted">Cadastrado em</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-history me-2"></i>
                        {{ $member->created_at->format('d/m/Y H:i') }}
                        <small class="text-muted ms-2">({{ $member->created_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Estatísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $member->schedules->count() }}</h4>
                            <small class="text-muted">Agendamentos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $member->schedules->where('status', 'completed')->count() }}</h4>
                        <small class="text-muted">Concluídos</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $member->schedules->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Pendentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger mb-0">{{ $member->schedules->where('status', 'cancelled')->count() }}</h4>
                        <small class="text-muted">Cancelados</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Agendamentos -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Histórico de Agendamentos
                </h5>
                <span class="badge bg-primary">{{ $member->schedules->count() }} agendamentos</span>
            </div>
            <div class="card-body">
                @if($member->schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->schedules->sortByDesc('schedule_date') as $schedule)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->schedule_date->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $schedule->schedule_time->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $schedule->service->service_name }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($schedule->service->description, 50) }}</small>
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
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h6 class="mt-3 text-muted">Nenhum agendamento encontrado</h6>
                        <p class="text-muted">Este membro ainda não possui agendamentos.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notas do Membro -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-text me-2"></i>
                    Notas e Observações
                </h5>
                <button class="btn btn-sm btn-outline-primary" onclick="addNote()">
                    <i class="bi bi-plus me-1"></i>
                    Adicionar Nota
                </button>
            </div>
            <div class="card-body">
                @if($member->notes && $member->notes->count() > 0)
                    @foreach($member->notes->sortByDesc('created_at') as $note)
                    <div class="border-start border-primary border-3 ps-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <p class="mb-1">{{ $note->content }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $note->created_at->format('d/m/Y H:i') }}
                                    @if($note->admin_user)
                                        por {{ $note->admin_user->username }}
                                    @endif
                                </small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNote({{ $note->note_id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-chat" style="font-size: 2rem; color: #dee2e6;"></i>
                        <p class="text-muted mt-2">Nenhuma nota adicionada</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Nota -->
<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-chat-text me-2"></i>
                    Adicionar Nota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="noteForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Conteúdo da Nota</label>
                        <textarea class="form-control" id="noteContent" name="content" rows="4" 
                                  placeholder="Digite sua observação sobre este membro..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Salvar Nota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script>
function confirmSchedule(scheduleId) {
    if (confirm('Confirmar este agendamento?')) {
        fetch(`/admin/schedules/${scheduleId}/confirm`, {
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
                alert('Erro ao confirmar agendamento');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao confirmar agendamento');
        });
    }
}

function addNote() {
    new bootstrap.Modal(document.getElementById('noteModal')).show();
}

function deleteNote(noteId) {
    if (confirm('Tem certeza que deseja excluir esta nota?')) {
        fetch(`/admin/notes/${noteId}`, {
            method: 'DELETE',
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
                alert('Erro ao excluir nota');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir nota');
        });
    }
}

// Formulário de nota
document.getElementById('noteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const content = document.getElementById('noteContent').value;
    
    fetch(`/admin/members/{{ $member->member_id }}/notes`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao salvar nota');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao salvar nota');
    });
});
</script>
@endpush