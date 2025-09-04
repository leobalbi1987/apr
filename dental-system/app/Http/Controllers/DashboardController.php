<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Service;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    public function index()
    {
        $member = Auth::guard('member')->user();
        
        // Estatísticas dos agendamentos
        $totalSchedules = Schedule::where('member_id', $member->member_id)->count();
        $confirmedSchedules = Schedule::where('member_id', $member->member_id)
            ->where('status', 'confirmed')
            ->count();
        $pendingSchedules = Schedule::where('member_id', $member->member_id)
            ->where('status', 'pending')
            ->count();
        
        // Próximo agendamento
        $nextSchedule = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->first();
        
        // Agendamentos recentes
        $schedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->take(5)
            ->get();

        // Próximos agendamentos
        $upcomingSchedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->where('date', '>=', now()->toDateString())
            ->where('status', 'pending')
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();
        
        // Serviços disponíveis
        $services = Service::orderBy('service_offer', 'asc')
            ->get();

        return view('dashboard', compact(
            'member', 
            'schedules', 
            'upcomingSchedules',
            'totalSchedules',
            'confirmedSchedules',
            'pendingSchedules',
            'nextSchedule',
            'services'
        ));
    }

    public function mySchedules()
    {
        $member = Auth::guard('member')->user();
        $schedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);

        return view('schedules.my-schedules', compact('schedules'));
    }
}
