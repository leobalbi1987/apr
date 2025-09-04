<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    public function index()
    {
        $member = Auth::guard('member')->user();
        $schedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);
        
        $services = Service::all();
        return view('schedules.index', compact('schedules', 'services'));
    }

    public function create()
    {
        $member = Auth::guard('member')->user();
        $schedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);
        
        $services = Service::all();
        return view('schedules.index', compact('schedules', 'services'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,service_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'note' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar se já existe agendamento no mesmo horário
        $existingSchedule = Schedule::where('date', $request->date)
            ->where('time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingSchedule) {
            return back()->withErrors([
                'time' => 'Este horário já está ocupado. Por favor, escolha outro horário.'
            ])->withInput();
        }

        // Verificar horário de funcionamento (8:00 às 17:00)
        $time = Carbon::createFromFormat('H:i', $request->time);
        $startTime = Carbon::createFromFormat('H:i', '08:00');
        $endTime = Carbon::createFromFormat('H:i', '17:00');

        if ($time->lt($startTime) || $time->gt($endTime)) {
            return back()->withErrors([
                'time' => 'Horário deve estar entre 08:00 e 17:00.'
            ])->withInput();
        }

        $member = Auth::guard('member')->user();
        
        Schedule::create([
            'member_id' => $member->member_id,
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
            'note' => $request->note
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function show(Schedule $schedule)
    {
        $member = Auth::guard('member')->user();
        
        if ($schedule->member_id !== $member->member_id) {
            abort(403, 'Acesso negado.');
        }

        $schedule->load('service');
        return view('schedules.my-schedules', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $member = Auth::guard('member')->user();
        
        if ($schedule->member_id !== $member->member_id) {
            abort(403, 'Acesso negado.');
        }

        if ($schedule->status !== 'pending') {
            return redirect()->route('schedules.index')
                ->with('error', 'Apenas agendamentos pendentes podem ser editados.');
        }

        $schedules = Schedule::with(['service'])
            ->where('member_id', $member->member_id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);
        
        $services = Service::all();
        return view('schedules.index', compact('schedule', 'schedules', 'services'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $member = Auth::guard('member')->user();
        
        if ($schedule->member_id !== $member->member_id) {
            abort(403, 'Acesso negado.');
        }

        if ($schedule->status !== 'pending') {
            return redirect()->route('schedules.index')
                ->with('error', 'Apenas agendamentos pendentes podem ser editados.');
        }

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,service_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'note' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar se já existe agendamento no mesmo horário (exceto o atual)
        $existingSchedule = Schedule::where('date', $request->date)
            ->where('time', $request->time)
            ->where('schedule_id', '!=', $schedule->schedule_id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingSchedule) {
            return back()->withErrors([
                'time' => 'Este horário já está ocupado. Por favor, escolha outro horário.'
            ])->withInput();
        }

        $schedule->update([
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
            'note' => $request->note
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function cancel(Schedule $schedule)
    {
        $member = Auth::guard('member')->user();
        
        if ($schedule->member_id !== $member->member_id) {
            abort(403, 'Acesso negado.');
        }

        if ($schedule->status !== 'pending') {
            return redirect()->route('schedules.index')
                ->with('error', 'Apenas agendamentos pendentes podem ser cancelados.');
        }

        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('schedules.index')
            ->with('success', 'Agendamento cancelado com sucesso!');
    }
}
