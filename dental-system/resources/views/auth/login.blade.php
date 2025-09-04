@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-heart-pulse" style="font-size: 3rem; color: #667eea;"></i>
                        <h3 class="mt-3 mb-1">Login Membro</h3>
                        <p class="text-muted">Entre com suas credenciais</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email
                            </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
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
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Entrar
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-2">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    Esqueceu sua senha?
                                </a>
                            </p>
                            <p class="mb-0">
                                Não tem conta? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                                    Registre-se aqui
                                </a>
                            </p>
                            <hr class="my-3">
                            <p class="mb-0">
                                <a href="{{ route('admin.login') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    Acesso Administrativo
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