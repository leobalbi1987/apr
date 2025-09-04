<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\Member;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::guard('member')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não coincidem com nossos registros.'
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
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
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $member = Member::create([
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

        Auth::guard('member')->login($member);

        return redirect('/dashboard')->with('success', 'Conta criada com sucesso!');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Verificar se o email existe na tabela members
        $member = Member::where('email', $request->email)->first();
        
        if (!$member) {
            return back()->withErrors(['email' => 'Não encontramos um usuário com esse endereço de email.']);
        }

        // Simular envio de email (você pode implementar o envio real depois)
        return back()->with('status', 'Link de recuperação enviado! Verifique seu email.');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Verificar se o email existe
        $member = Member::where('email', $request->email)->first();
        
        if (!$member) {
            return back()->withErrors(['email' => 'Não encontramos um usuário com esse endereço de email.']);
        }

        // Atualizar a senha (em uma implementação real, você verificaria o token)
        $member->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')->with('status', 'Sua senha foi redefinida com sucesso!');
    }
}
