@extends('layouts.app')

@section('title', 'Gerenciar Membros')
@section('page-title', 'Gerenciar Membros')

@section('page-actions')
<a href="{{ route('admin.members.create') }}" class="btn btn-primary">
    <i class="bi bi-person-plus me-2"></i>
    Novo Membro
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
                                <p class="mb-0">Total de Membros</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">{{ $stats['active'] }}</h4>
                                <p class="mb-0">Membros Ativos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-check" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">{{ $stats['new_this_month'] }}</h4>
                                <p class="mb-0">Novos este Mês</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-plus" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">{{ $stats['with_schedules'] }}</h4>
                                <p class="mb-0">Com Agendamentos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.members.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Nome, email ou telefone" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Cadastro de</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Cadastro até</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Membros -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    Lista de Membros
                </h5>
                <span class="badge bg-primary">{{ $members->total() }} membros</span>
            </div>
            <div class="card-body">
                @if($members->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Membro</th>
                                    <th>Contato</th>
                                    <th>Cadastro</th>
                                    <th>Agendamentos</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $member->full_name }}</strong><br>
                                                <small class="text-muted">ID: {{ $member->member_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-envelope me-1"></i>
                                            <a href="mailto:{{ $member->email }}">{{ $member->email }}</a><br>
                                            <i class="bi bi-telephone me-1"></i>
                                            <a href="tel:{{ $member->phone }}">{{ $member->phone }}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $member->created_at->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $member->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge bg-info">{{ $member->schedules_count }}</span><br>
                                            <small class="text-muted">agendamentos</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($member->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Ativo
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.members.show', $member->member_id) }}" 
                                               class="btn btn-outline-primary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.members.edit', $member->member_id) }}" 
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteMember({{ $member->member_id }})" 
                                                    title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $members->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">Nenhum membro encontrado</h5>
                        <p class="text-muted">Não há membros cadastrados ou nenhum corresponde aos filtros aplicados.</p>
                        <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Cadastrar Primeiro Membro
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
function deleteMember(memberId) {
    if (confirm('Tem certeza que deseja excluir este membro? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/members/${memberId}`, {
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
                alert('Erro ao excluir membro: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir membro');
        });
    }
}
</script>
@endpush