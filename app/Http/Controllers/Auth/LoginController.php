<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsActivity;

class LoginController extends Controller
{
    use LogsActivity;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $unit = Unit::where('username', $credentials['login'])
                    ->orWhere('email', $credentials['login'])
                    ->first();

        if ($unit && Hash::check($credentials['password'], $unit->password)) {

            // === CEK VERIFIKASI EMAIL ===
            if ($unit->email_verified_at === null) {
                return back()->withErrors([
                    'login' => 'Email belum diverifikasi. Silakan cek inbox atau folder spam untuk link verifikasi.'
                ])->withInput();
            }

            session()->regenerate();

            session([
                'unit_id'   => $unit->id,
                'unit_name' => $unit->school_name
            ]);

            $unit->autoRegisterGPS();

            $this->logActivity('login', 'auth', 'User login berhasil', [
                'unit_id' => $unit->id,
                'username' => $unit->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($unit->is_admin) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, Admin!');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang, ' . $unit->school_name . '!');
        }

        Log::warning('Login gagal', [
            'login' => $credentials['login'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors(['login' => 'Username/email atau password salah.'])->withInput();
    }

    public function logout()
    {
        if (session('unit_id')) {
            $this->logActivity('logout', 'auth', 'User logout', [
                'unit_id' => session('unit_id'),
                'ip' => request()->ip()
            ]);
        }

        session()->forget(['unit_id', 'unit_name']);

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}