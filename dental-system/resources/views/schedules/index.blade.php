@extends('layouts.app')

@section('title', 'Agendamentos')
@section('page-title', 'Fazer Agendamento')

@section('page-actions')
<a href="{{ route('my-schedules') }}" class="btn btn-outline-primary">
    <i class="bi bi-clock-history me-2"></i>
    Meus Agendamentos
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Novo Agendamento
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('schedules.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="service_id" class="form-label">
                                <i class="bi bi-gear me-1"></i>
                                Serviço *
                            </label>
                            <select id="service_id" name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">Selecione um serviço</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->service_id }}" 
                                            data-price="{{ $service->price }}"
                                            {{ old('service_id') == $service->service_id ? 'selected' : '' }}>
                                        {{ $service->service_name }} - R$ {{ number_format($service->price, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="schedule_date" class="form-label">
                                <i class="bi bi-calendar me-1"></i>
                                Data *
                            </label>
                            <input type="date" id="schedule_date" name="schedule_date" 
                                   class="form-control @error('schedule_date') is-invalid @enderror" 
                                   value="{{ old('schedule_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('schedule_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="schedule_time" class="form-label">
                                <i class="bi bi-clock me-1"></i>
                                Horário *
                            </label>
                            <select id="schedule_time" name="schedule_time" class="form-select @error('schedule_time') is-invalid @enderror" required>
                                <option value="">Selecione um horário</option>
                                <option value="08:00" {{ old('schedule_time') == '08:00' ? 'selected' : '' }}>08:00</option>
                                <option value="08:30" {{ old('schedule_time') == '08:30' ? 'selected' : '' }}>08:30</option>
                                <option value="09:00" {{ old('schedule_time') == '09:00' ? 'selected' : '' }}>09:00</option>
                                <option value="09:30" {{ old('schedule_time') == '09:30' ? 'selected' : '' }}>09:30</option>
                                <option value="10:00" {{ old('schedule_time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                <option value="10:30" {{ old('schedule_time') == '10:30' ? 'selected' : '' }}>10:30</option>
                                <option value="11:00" {{ old('schedule_time') == '11:00' ? 'selected' : '' }}>11:00</option>
                                <option value="11:30" {{ old('schedule_time') == '11:30' ? 'selected' : '' }}>11:30</option>
                                <option value="14:00" {{ old('schedule_time') == '14:00' ? 'selected' : '' }}>14:00</option>
                                <option value="14:30" {{ old('schedule_time') == '14:30' ? 'selected' : '' }}>14:30</option>
                                <option value="15:00" {{ old('schedule_time') == '15:00' ? 'selected' : '' }}>15:00</option>
                                <option value="15:30" {{ old('schedule_time') == '15:30' ? 'selected' : '' }}>15:30</option>
                                <option value="16:00" {{ old('schedule_time') == '16:00' ? 'selected' : '' }}>16:00</option>
                                <option value="16:30" {{ old('schedule_time') == '16:30' ? 'selected' : '' }}>16:30</option>
                                <option value="17:00" {{ old('schedule_time') == '17:00' ? 'selected' : '' }}>17:00</option>
                                <option value="17:30" {{ old('schedule_time') == '17:30' ? 'selected' : '' }}>17:30</option>
                            </select>
                            @error('schedule_time')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-cash me-1"></i>
                                Valor Estimado
                            </label>
                            <div class="form-control bg-light" id="estimated-price">
                                <span class="text-muted">Selecione um serviço</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            <i class="bi bi-chat-text me-1"></i>
                            Observações
                        </label>
                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3" placeholder="Descreva sintomas, preferências ou observações importantes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Importante:</strong> Seu agendamento será enviado para análise e você receberá uma confirmação em breve.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-check me-2"></i>
                            Confirmar Agendamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Serviços Disponíveis -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Serviços Disponíveis
                </h5>
            </div>
            <div class="card-body">
                @foreach($services as $service)
                <div class="mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $service->service_name }}</h6>
                            <p class="text-muted small mb-2">{{ $service->description }}</p>
                        </div>
                        <div class="text-end">
                            <strong class="text-primary">R$ {{ number_format($service->price, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Horários de Funcionamento -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Horários de Funcionamento
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <strong>Segunda a Sexta</strong><br>
                        <span class="text-muted">08:00 - 12:00</span><br>
                        <span class="text-muted">14:00 - 18:00</span>
                    </div>
                    <div class="col-12">
                        <strong>Sábado</strong><br>
                        <span class="text-muted">08:00 - 12:00</span>
                    </div>
                </div>
                <hr>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <small>Domingos e feriados: Fechado</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const priceDisplay = document.getElementById('estimated-price');
    
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const formattedPrice = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(price);
            priceDisplay.innerHTML = `<strong class="text-success">${formattedPrice}</strong>`;
        } else {
            priceDisplay.innerHTML = '<span class="text-muted">Selecione um serviço</span>';
        }
    });
    
    // Desabilitar domingos no seletor de data
    const dateInput = document.getElementById('schedule_date');
    dateInput.addEventListener('input', function() {
        const selectedDate = new Date(this.value);
        if (selectedDate.getDay() === 0) { // Domingo
            alert('Não atendemos aos domingos. Por favor, selecione outro dia.');
            this.value = '';
        }
    });
});
</script>
@endpush