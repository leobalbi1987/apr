<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminDashboardController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação para membros
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rotas de recuperação de senha
    Route::get('/password/reset', function () {
        return view('auth.passwords.email');
    })->name('password.request');
    
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
});

// Rotas de autenticação para administradores
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Rotas protegidas do admin
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Gerenciamento de Membros
        Route::get('/members', [AdminDashboardController::class, 'members'])->name('admin.members.index');
        Route::get('/members/create', [AdminDashboardController::class, 'createMember'])->name('admin.members.create');
        Route::post('/members', [AdminDashboardController::class, 'storeMember'])->name('admin.members.store');
        Route::get('/members/{member}', [AdminDashboardController::class, 'showMember'])->name('admin.members.show');
        Route::get('/members/{member}/edit', [AdminDashboardController::class, 'editMember'])->name('admin.members.edit');
        Route::put('/members/{member}', [AdminDashboardController::class, 'updateMember'])->name('admin.members.update');
        Route::delete('/members/{member}', [AdminDashboardController::class, 'deleteMember'])->name('admin.members.delete');
        
        // Gerenciamento de Agendamentos
        Route::get('/schedules', [AdminDashboardController::class, 'schedules'])->name('admin.schedules.index');
        Route::get('/schedules/{schedule}', [AdminDashboardController::class, 'showSchedule'])->name('admin.schedules.show');
        Route::put('/schedules/{schedule}/status', [AdminDashboardController::class, 'updateScheduleStatus'])->name('admin.schedules.status');
        
        // Gerenciamento de Serviços
        Route::get('/services', [AdminDashboardController::class, 'services'])->name('admin.services');
        Route::get('/services/create', [AdminDashboardController::class, 'createService'])->name('admin.services.create');
        Route::post('/services', [AdminDashboardController::class, 'storeService'])->name('admin.services.store');
        Route::get('/services/{service}/edit', [AdminDashboardController::class, 'editService'])->name('admin.services.edit');
        Route::put('/services/{service}', [AdminDashboardController::class, 'updateService'])->name('admin.services.update');
        Route::delete('/services/{service}', [AdminDashboardController::class, 'deleteService'])->name('admin.services.delete');
        
        // Gerenciamento de Notas
        Route::get('/notes', [AdminDashboardController::class, 'notes'])->name('admin.notes.index');
        Route::get('/notes/create', [AdminDashboardController::class, 'createNote'])->name('admin.notes.create');
        Route::post('/notes', [AdminDashboardController::class, 'storeNote'])->name('admin.notes.store');
        Route::delete('/notes/{note}', [AdminDashboardController::class, 'deleteNote'])->name('admin.notes.delete');
    });
});

// Rotas protegidas para membros
Route::middleware('auth:member')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-schedules', [DashboardController::class, 'mySchedules'])->name('my-schedules');
    
    // Rotas de agendamento
    Route::resource('schedules', ScheduleController::class)->except(['destroy']);
    Route::put('/schedules/{schedule}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
});

// Redirecionamento para usuários não autenticados
Route::get('/home', function () {
    return redirect('/dashboard');
})->middleware('auth:member');

Route::get('/admin', function () {
    return redirect('/admin/dashboard');
})->middleware('auth:admin');
