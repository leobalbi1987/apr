@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus" style="font-size: 3rem; color: #667eea;"></i>
                        <h3 class="mt-3 mb-1">Criar Conta</h3>
                        <p class="text-muted">Preencha os dados para se registrar</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Nome
                                </label>
                                <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" 
                                       name="firstname" value="{{ old('firstname') }}" required autocomplete="given-name" autofocus>
                                @error('firstname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Sobrenome
                                </label>
                                <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" 
                                       name="lastname" value="{{ old('lastname') }}" required autocomplete="family-name">
                                @error('lastname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email
                            </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>
                                Nome de Usuário
                            </label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" 
                                   name="username" value="{{ old('username') }}" required autocomplete="username">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="middlename" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Nome do Meio (Opcional)
                                </label>
                                <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" 
                                       name="middlename" value="{{ old('middlename') }}" autocomplete="additional-name">
                                @error('middlename')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_no" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>
                                    Telefone
                                </label>
                                <input id="contact_no" type="text" class="form-control @error('contact_no') is-invalid @enderror" 
                                       name="contact_no" value="{{ old('contact_no') }}" required autocomplete="tel">
                                @error('contact_no')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Idade
                                </label>
                                <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" 
                                       name="age" value="{{ old('age') }}" required min="1" max="120">
                                @error('age')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">
                                    <i class="bi bi-gender-ambiguous me-1"></i>
                                    Gênero
                                </label>
                                <select id="gender" class="form-select @error('gender') is-invalid @enderror" 
                                        name="gender" required>
                                    <option value="">Selecione o gênero</option>
                                    <option value="Masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Feminino" {{ old('gender') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                    <option value="Outro" {{ old('gender') == 'Outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt me-1"></i>
                                Endereço
                            </label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                                      name="address" rows="2" required autocomplete="street-address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    Senha
                                </label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Confirmar Senha
                                </label>
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">
                                <i class="bi bi-calendar me-1"></i>
                                Data de Nascimento
                            </label>
                            <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                   name="birthdate" value="{{ old('birthdate') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" 
                                   name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms">
                                Concordo com os <a href="#" class="text-decoration-none">Termos de Uso</a> 
                                e <a href="#" class="text-decoration-none">Política de Privacidade</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>
                                Criar Conta
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Já tem conta? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Faça login aqui
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 1rem;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush