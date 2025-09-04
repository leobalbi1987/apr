@extends('layouts.app')

@section('title', 'Criar Novo Membro')
@section('page-title', 'Criar Novo Membro')

@section('page-actions')
<a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-2"></i>
    Voltar
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.members.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstname" class="form-label">Nome *</label>
                            <input type="text" name="firstname" id="firstname" class="form-control @error('firstname') is-invalid @enderror" 
                                   value="{{ old('firstname') }}" required>
                            @error('firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lastname" class="form-label">Sobrenome *</label>
                            <input type="text" name="lastname" id="lastname" class="form-control @error('lastname') is-invalid @enderror" 
                                   value="{{ old('lastname') }}" required>
                            @error('lastname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="middlename" class="form-label">Nome do Meio</label>
                        <input type="text" name="middlename" id="middlename" class="form-control @error('middlename') is-invalid @enderror" 
                               value="{{ old('middlename') }}">
                        @error('middlename')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Nome de Usuário *</label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" 
                               value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_no" class="form-label">Telefone *</label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control @error('contact_no') is-invalid @enderror" 
                                   value="{{ old('contact_no') }}" required>
                            @error('contact_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Idade *</label>
                            <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" 
                                   value="{{ old('age') }}" min="1" max="120" required>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gênero *</label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Selecione o gênero</option>
                            <option value="Masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Feminino" {{ old('gender') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                            <option value="Outro" {{ old('gender') == 'Outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço *</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Senha *</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.members.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Criar Membro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection