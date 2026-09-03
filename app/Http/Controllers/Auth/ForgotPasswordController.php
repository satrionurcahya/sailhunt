<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Traits\LogsActivity;

class ForgotPasswordController extends Controller
{
    use LogsActivity;

    /**
     * Tampilkan halaman lupa password.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:units,email',
        ]);

        $unit = Unit::where('email', $request->email)->first();

        // Buat token
        $token = Str::random(64);

        // Simpan token ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $unit->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Kirim email reset password
        try {
            Mail::to($unit->email)->send(new ResetPassword($unit, $token));

            $this->logActivity('forgot_password', 'auth', 'Mengirim link reset password', [
                'unit_id' => $unit->id,
                'email' => $unit->email,
            ]);

            return back()->with('success', 'Link reset password telah dikirim ke email ' . $unit->email . '. Cek inbox atau folder spam.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim reset password: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }
    }
}