@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock" style="font-size: 3rem; color: #dc3545;"></i>
                        <h3 class="mt-3 mb-1">Acesso Admin</h3>
                        <p class="text-muted">Área restrita - Administradores</p>
                    </div>

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>
                                Usuário
                            </label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" 
                                   name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>
                                Senha
                            </label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Lembrar de mim
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-shield-check me-2"></i>
                                Acessar Sistema
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>Área restrita para administradores autorizados</small>
                            </div>
                            <p class="mb-0">
                                <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Voltar ao Login de Membros
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
        background: linear-gradient(135deg, #dc3545 0%, #6f42c1 100%);
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 1rem;
    }
    .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #6f42c1 100%);
        border: none;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #5a2d91 100%);
    }
</style>
@endpush