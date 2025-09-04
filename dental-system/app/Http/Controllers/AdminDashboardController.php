<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\Note;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $totalMembers = Member::count();
        $totalSchedules = Schedule::count();
        $pendingSchedules = Schedule::where('status', 'pending')->count();
        $todaySchedules = Schedule::whereDate('date', today())->count();
        
        $recentSchedules = Schedule::with(['member', 'service'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingSchedules = Schedule::with(['member', 'service'])
            ->where('date', '>=', today())
            ->where('status', 'pending')
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->take(10)
            ->get();

        $stats = [
            'total_members' => $totalMembers,
            'total_schedules' => $totalSchedules,
            'pending_schedules' => $pendingSchedules,
            'today_schedules' => $todaySchedules
        ];

        return view('admin.dashboard', compact(
            'stats', 'recentSchedules', 'upcomingSchedules'
        ));
    }

    // Gerenciamento de Membros
    public function members()
    {
        $members = Member::orderBy('created_at', 'desc')->paginate(15);
        
        // Calcular estatísticas
        $totalMembers = Member::count();
        $activeMembers = $totalMembers; // All members are considered active since there's no is_active column
        $todayMembers = Member::whereDate('created_at', today())->count();
        $thisWeekMembers = Member::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newThisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $withSchedules = Member::whereHas('schedules')->count();
        
        $stats = [
            'total' => $totalMembers,
            'active' => $activeMembers,
            'today' => $todayMembers,
            'this_week' => $thisWeekMembers,
            'new_this_month' => $newThisMonth,
            'with_schedules' => $withSchedules
        ];
        
        return view('admin.members.index', compact('members', 'stats'));
    }

    public function showMember(Member $member)
    {
        $member->load(['schedules.service']);
        return view('admin.members.show', compact('member'));
    }

    public function createMember()
    {
        return view('admin.members.create');
    }

    public function storeMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'address' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:members',
            'contact_no' => 'required|string|max:100',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:members',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Member::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'age' => $request->age,
            'gender' => $request->gender,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membro criado com sucesso!');
    }

    public function editMember(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function updateMember(Request $request, Member $member)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'address' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:members,email,' . $member->member_id . ',member_id',
            'contact_no' => 'required|string|max:100',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:members,username,' . $member->member_id . ',member_id',
            'password' => 'nullable|string|min:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'age' => $request->age,
            'gender' => $request->gender,
            'username' => $request->username
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $member->update($updateData);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membro atualizado com sucesso!');
    }

    public function deleteMember(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', 'Membro excluído com sucesso!');
    }

    // Gerenciamento de Agendamentos
    public function schedules()
    {
        $schedules = Schedule::with(['member', 'service'])
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(15);
        
        // Calcular estatísticas
        $totalSchedules = Schedule::count();
        $pendingSchedules = Schedule::where('status', 'pending')->count();
        $confirmedSchedules = Schedule::where('status', 'confirmed')->count();
        $todaySchedules = Schedule::whereDate('date', today())->count();
        
        $stats = [
            'total' => $totalSchedules,
            'pending' => $pendingSchedules,
            'confirmed' => $confirmedSchedules,
            'today' => $todaySchedules
        ];
        
        // Buscar todos os serviços para o filtro
        $services = Service::orderBy('service_offer')->get();
        
        return view('admin.schedules.index', compact('schedules', 'stats', 'services'));
    }

    public function showSchedule(Schedule $schedule)
    {
        $schedule->load(['member', 'service']);
        return view('admin.schedules.show', compact('schedule'));
    }

    public function updateScheduleStatus(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $schedule->update(['status' => $request->status]);

        return redirect()->route('admin.schedules')
            ->with('success', 'Status do agendamento atualizado com sucesso!');
    }

    // Gerenciamento de Serviços
    public function services()
    {
        $services = Service::orderBy('service_offer', 'asc')->paginate(15);
        
        // Calcular estatísticas
        $totalServices = Service::count();
        $activeServices = $totalServices; // All services are considered active since there's no is_active column
        $avgPrice = Service::avg('price') ?? 0;
        
        // Serviço mais popular (baseado no número de agendamentos)
        $mostPopular = Service::withCount('schedules')
            ->orderBy('schedules_count', 'desc')
            ->first();
        
        // Ensure mostPopular is a Service object, not a string
        $mostPopularName = 'N/A';
        if ($mostPopular && is_object($mostPopular) && isset($mostPopular->service_offer)) {
            $mostPopularName = $mostPopular->service_offer;
        }
        
        $stats = [
            'total' => $totalServices,
            'active' => $activeServices,
            'most_popular' => $mostPopularName,
            'avg_price' => number_format($avgPrice, 2)
        ];
        
        return view('admin.services.index', compact('services', 'stats'));
    }

    public function createService()
    {
        return view('admin.services.create');
    }

    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_offer' => 'required|string|max:100',
            'price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Service::create([
            'service_offer' => $request->service_offer,
            'price' => $request->price
        ]);

        return redirect()->route('admin.services')
            ->with('success', 'Serviço criado com sucesso!');
    }

    public function editService(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function updateService(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'service_offer' => 'required|string|max:100',
            'price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $service->update([
            'service_offer' => $request->service_offer,
            'price' => $request->price
        ]);

        return redirect()->route('admin.services')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services')
            ->with('success', 'Serviço excluído com sucesso!');
    }

    // Gerenciamento de Notas
    public function notes()
    {
        $notes = Note::orderBy('date_created', 'desc')->paginate(15);
        $members = Member::orderBy('first_name')->get();
        
        // Calcular estatísticas
        $totalNotes = Note::count();
        $todayNotes = Note::whereDate('date_created', today())->count();
        
        $stats = [
            'total' => $totalNotes,
            'today' => $todayNotes,
            'this_week' => Note::whereBetween('date_created', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'members_with_notes' => $totalNotes // Since there's no patient relationship, use total notes
        ];
        
        return view('admin.notes.index', compact('notes', 'stats', 'members'));
    }

    public function createNote()
    {
        return view('admin.notes.create');
    }

    public function storeNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:200'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Note::create([
            'message' => $request->message,
            'date_created' => now()
        ]);

        return redirect()->route('admin.notes.index')
            ->with('success', 'Nota criada com sucesso!');
    }

    public function deleteNote(Note $note)
    {
        $note->delete();
        return redirect()->route('admin.notes.index')
            ->with('success', 'Nota excluída com sucesso!');
    }
}
