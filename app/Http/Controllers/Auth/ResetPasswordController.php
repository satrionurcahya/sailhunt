<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class ResetPasswordController extends Controller
{
    use LogsActivity;

    /**
     * Tampilkan halaman reset password.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses reset password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:units,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        // Cek token di tabel password_reset_tokens
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid atau sudah kadaluarsa.',
            ]);
        }

        // Cek apakah token masih berlaku (60 menit)
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->diffInMinutes(now()) > 60) {
            // Hapus token yang kadaluarsa
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors([
                'email' => 'Link reset password sudah kadaluarsa (lebih dari 60 menit). Silakan minta ulang.',
            ]);
        }

        // Update password unit
        $unit = Unit::where('email', $request->email)->first();
        $unit->password = $request->password; // Mutator otomatis bcrypt
        $unit->save();

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Log aktivitas
        $this->logActivity('password_reset', 'auth', 'Reset password berhasil', [
            'unit_id' => $unit->id,
            'email' => $unit->email,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}