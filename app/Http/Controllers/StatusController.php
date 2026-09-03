<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Upload;
use App\Models\Registration;

class StatusController extends Controller
{
    public function index()
    {
        $unit = Unit::findOrFail(session('unit_id'));

        $daftarUlang = Upload::where('unit_id', $unit->id)
            ->where('type', 'daftar_ulang')
            ->latest()
            ->first();

        $registrations = Registration::where('unit_id', $unit->id)
            ->with('competition')
            ->get();

        // Total biaya lomba yang belum lunas
        $totalTagihan = $registrations->where('payment_status', '!=', 'verified')->sum(function ($reg) {
            return $reg->competition->fee;
        });

        // Rincian lomba yang belum lunas
        $pendingPayments = $registrations->where('payment_status', '!=', 'verified');

        // Terbilang total tagihan
        $terbilang = $this->terbilang($totalTagihan) . ' Rupiah';

        return view('dashboard.status', compact('unit', 'daftarUlang', 'registrations', 'totalTagihan', 'pendingPayments', 'terbilang'));
    }

    /**
     * Mengubah angka menjadi terbilang Bahasa Indonesia.
     */
    private function terbilang($angka)
    {
        $angka = abs((int) $angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $terbilang = '';

        if ($angka < 12) {
            $terbilang = $baca[$angka];
        } elseif ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            $puluh = $this->terbilang(floor($angka / 10)) . ' Puluh';
            $satuan = $this->terbilang($angka % 10);
            $terbilang = $puluh . ($satuan ? ' ' . $satuan : '');
        } elseif ($angka < 200) {
            $terbilang = 'Seratus ' . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $ratus = $this->terbilang(floor($angka / 100)) . ' Ratus';
            $sisa = $this->terbilang($angka % 100);
            $terbilang = $ratus . ($sisa ? ' ' . $sisa : '');
        } elseif ($angka < 2000) {
            $terbilang = 'Seribu ' . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $ribu = $this->terbilang(floor($angka / 1000)) . ' Ribu';
            $sisa = $this->terbilang($angka % 1000);
            $terbilang = $ribu . ($sisa ? ' ' . $sisa : '');
        } elseif ($angka < 1000000000) {
            $juta = $this->terbilang(floor($angka / 1000000)) . ' Juta';
            $sisa = $this->terbilang($angka % 1000000);
            $terbilang = $juta . ($sisa ? ' ' . $sisa : '');
        } elseif ($angka < 1000000000000) {
            $miliar = $this->terbilang(floor($angka / 1000000000)) . ' Miliar';
            $sisa = $this->terbilang($angka % 1000000000);
            $terbilang = $miliar . ($sisa ? ' ' . $sisa : '');
        }

        return trim($terbilang);
    }
}