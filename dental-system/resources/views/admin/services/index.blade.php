@extends('layouts.app')

@section('title', 'Gerenciar Serviços')
@section('page-title', 'Gerenciar Serviços')

@section('page-actions')
<a href="{{ route('admin.services.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>
    Novo Serviço
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
                                <p class="mb-0">Total de Serviços</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-gear" style="font-size: 2rem;"></i>
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
                                <p class="mb-0">Serviços Ativos</p>
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
                                <h4 class="mb-0">{{ $stats['most_popular'] }}</h4>
                                <p class="mb-0">Mais Popular</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-star" style="font-size: 2rem;"></i>
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
                                <h4 class="mb-0">R$ {{ number_format($stats['avg_price'], 2, ',', '.') }}</h4>
                                <p class="mb-0">Preço Médio</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cash" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.services') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Nome ou descrição do serviço" value="{{ request('search') }}">
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
                        <label for="price_min" class="form-label">Preço mín.</label>
                        <input type="number" name="price_min" id="price_min" class="form-control" 
                               step="0.01" placeholder="0,00" value="{{ request('price_min') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="price_max" class="form-label">Preço máx.</label>
                        <input type="number" name="price_max" id="price_max" class="form-control" 
                               step="0.01" placeholder="999,99" value="{{ request('price_max') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Serviços -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Lista de Serviços
                </h5>
                <span class="badge bg-primary">{{ $services->total() }} serviços</span>
            </div>
            <div class="card-body">
                @if($services->count() > 0)
                    <div class="row">
                        @foreach($services as $service)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 service-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-truncate">{{ $service->service_name }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.services.show', $service->service_id) }}">
                                                    <i class="bi bi-eye me-2"></i>Ver Detalhes
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.services.edit', $service->service_id) }}">
                                                    <i class="bi bi-pencil me-2"></i>Editar
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" 
                                                        onclick="deleteService({{ $service->service_id }})">
                                                    <i class="bi bi-trash me-2"></i>Excluir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($service->description, 100) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="text-success mb-0">
                                                R$ {{ number_format($service->price, 2, ',', '.') }}
                                            </h5>
                                            <small class="text-muted">Preço do serviço</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Ativo
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center border-top pt-3">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="text-primary mb-0">{{ $service->schedules_count }}</h6>
                                                <small class="text-muted">Agendamentos</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-info mb-0">
                                                R$ {{ number_format($service->schedules_count * $service->price, 2, ',', '.') }}
                                            </h6>
                                            <small class="text-muted">Receita Total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock-history me-1"></i>
                                            Criado {{ $service->created_at->diffForHumans() }}
                                        </small>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.services.show', $service->service_id) }}" 
                                               class="btn btn-outline-primary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.services.edit', $service->service_id) }}" 
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteService({{ $service->service_id }})" 
                                                    title="Excluir serviço">
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
                        {{ $services->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gear" style="font-size: 4rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">Nenhum serviço encontrado</h5>
                        <p class="text-muted">Não há serviços cadastrados ou nenhum corresponde aos filtros aplicados.</p>
                        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Cadastrar Primeiro Serviço
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
.service-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.service-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.service-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
function deleteService(serviceId) {
    if (confirm('Tem certeza que deseja excluir este serviço? Esta ação não pode ser desfeita e afetará todos os agendamentos relacionados.')) {
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
                location.reload();
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

function toggleServiceStatus(serviceId) {
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
</script>
@endpush