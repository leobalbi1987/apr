@extends('layouts.app')

@section('title', 'Gerenciar Notas')
@section('page-title', 'Gerenciar Notas')

@section('page-actions')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
    <i class="bi bi-plus-circle me-2"></i>
    Nova Nota
</button>
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
                                <p class="mb-0">Total de Notas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">{{ $stats['today'] }}</h4>
                                <p class="mb-0">Notas de Hoje</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">{{ $stats['this_week'] }}</h4>
                                <p class="mb-0">Esta Semana</p>
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
                                <h4 class="mb-0">{{ $stats['members_with_notes'] }}</h4>
                                <p class="mb-0">Membros com Notas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.notes.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Conteúdo da nota ou nome do membro" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="member" class="form-label">Membro</label>
                        <select name="member" id="member" class="form-select">
                            <option value="">Todos os membros</option>
                            @foreach($members as $member)
                                <option value="{{ $member->member_id }}" 
                                        {{ request('member') == $member->member_id ? 'selected' : '' }}>
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Data inicial</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Data final</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <label for="per_page" class="form-label">Por página</label>
                        <select name="per_page" id="per_page" class="form-select">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Notas -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-journal-text me-2"></i>
                    Lista de Notas
                </h5>
                <span class="badge bg-primary">{{ $notes->total() }} notas</span>
            </div>
            <div class="card-body">
                @if($notes->count() > 0)
                    <div class="row">
                        @foreach($notes as $note)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 note-card">
                                <div class="card-header d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.members.show', $note->member->member_id) }}" 
                                               class="text-decoration-none">
                                                {{ $note->member->first_name }} {{ $note->member->last_name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $note->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" 
                                                        onclick="viewNote({{ $note->note_id }})">
                                                    <i class="bi bi-eye me-2"></i>Ver Completa
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" 
                                                        onclick="editNote({{ $note->note_id }})">
                                                    <i class="bi bi-pencil me-2"></i>Editar
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" 
                                                        onclick="deleteNote({{ $note->note_id }})">
                                                    <i class="bi bi-trash me-2"></i>Excluir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        {{ Str::limit($note->note_content, 120) }}
                                    </p>
                                    
                                    @if($note->schedule)
                                        <div class="mt-3 p-2 bg-light rounded">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                Relacionada ao agendamento:
                                            </small>
                                            <small>
                                                <strong>{{ $note->schedule->service->service_name }}</strong><br>
                                                {{ $note->schedule->schedule_date->format('d/m/Y') }} às {{ $note->schedule->schedule_time }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $note->created_at->diffForHumans() }}
                                        </small>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="viewNote({{ $note->note_id }})" 
                                                    title="Ver completa">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" 
                                                    onclick="editNote({{ $note->note_id }})" 
                                                    title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteNote({{ $note->note_id }})" 
                                                    title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notes->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">Nenhuma nota encontrada</h5>
                        <p class="text-muted">Não há notas cadastradas ou nenhuma corresponde aos filtros aplicados.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            Criar Primeira Nota
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Nota -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nova Nota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addNoteForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_member_id" class="form-label">Membro *</label>
                            <select name="member_id" id="add_member_id" class="form-select" required>
                                <option value="">Selecione um membro</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->member_id }}">
                                        {{ $member->first_name }} {{ $member->last_name }} - {{ $member->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_schedule_id" class="form-label">Agendamento (opcional)</label>
                            <select name="schedule_id" id="add_schedule_id" class="form-select">
                                <option value="">Nenhum agendamento</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="add_note_content" class="form-label">Conteúdo da Nota *</label>
                        <textarea name="note_content" id="add_note_content" class="form-control" 
                                  rows="6" required placeholder="Digite o conteúdo da nota..."></textarea>
                        <div class="form-text">Descreva observações importantes sobre o membro ou agendamento.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Salvar Nota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver/Editar Nota -->
<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModalTitle">
                    <i class="bi bi-journal-text me-2"></i>
                    Detalhes da Nota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="noteModalBody">
                <!-- Conteúdo carregado via AJAX -->
            </div>
            <div class="modal-footer" id="noteModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.note-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.note-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.note-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
// Carregar agendamentos quando selecionar membro
document.getElementById('add_member_id').addEventListener('change', function() {
    const memberId = this.value;
    const scheduleSelect = document.getElementById('add_schedule_id');
    
    // Limpar opções
    scheduleSelect.innerHTML = '<option value="">Carregando...</option>';
    
    if (memberId) {
        fetch(`/admin/members/${memberId}/schedules`)
            .then(response => response.json())
            .then(data => {
                scheduleSelect.innerHTML = '<option value="">Nenhum agendamento</option>';
                data.schedules.forEach(schedule => {
                    const option = document.createElement('option');
                    option.value = schedule.schedule_id;
                    option.textContent = `${schedule.service.service_name} - ${schedule.schedule_date} ${schedule.schedule_time}`;
                    scheduleSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                scheduleSelect.innerHTML = '<option value="">Erro ao carregar</option>';
            });
    } else {
        scheduleSelect.innerHTML = '<option value="">Nenhum agendamento</option>';
    }
});

// Adicionar nova nota
document.getElementById('addNoteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/notes', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao salvar nota: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao salvar nota');
    });
});

function viewNote(noteId) {
    fetch(`/admin/notes/${noteId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const note = data.note;
                document.getElementById('noteModalTitle').innerHTML = `
                    <i class="bi bi-journal-text me-2"></i>
                    Nota de ${note.member.first_name} ${note.member.last_name}
                `;
                
                let scheduleInfo = '';
                if (note.schedule) {
                    scheduleInfo = `
                        <div class="alert alert-info">
                            <i class="bi bi-calendar-check me-2"></i>
                            <strong>Agendamento relacionado:</strong><br>
                            ${note.schedule.service.service_name} - ${note.schedule.schedule_date} às ${note.schedule.schedule_time}
                        </div>
                    `;
                }
                
                document.getElementById('noteModalBody').innerHTML = `
                    <div class="mb-3">
                        <h6 class="text-muted">Membro</h6>
                        <p><strong>${note.member.first_name} ${note.member.last_name}</strong> - ${note.member.email}</p>
                    </div>
                    ${scheduleInfo}
                    <div class="mb-3">
                        <h6 class="text-muted">Conteúdo da Nota</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">${note.note_content}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Criada em</h6>
                            <p>${note.created_at}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Atualizada em</h6>
                            <p>${note.updated_at}</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('noteModalFooter').innerHTML = `
                    <button type="button" class="btn btn-warning" onclick="editNote(${noteId})">
                        <i class="bi bi-pencil me-2"></i>
                        Editar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Fechar
                    </button>
                `;
                
                new bootstrap.Modal(document.getElementById('noteModal')).show();
            } else {
                alert('Erro ao carregar nota: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao carregar nota');
        });
}

function editNote(noteId) {
    // Implementar edição de nota
    alert('Funcionalidade de edição será implementada em breve.');
}

function deleteNote(noteId) {
    if (confirm('Tem certeza que deseja excluir esta nota? Esta ação não pode ser desfeita.')) {
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
                alert('Erro ao excluir nota: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir nota');
        });
    }
}
</script>
@endpush