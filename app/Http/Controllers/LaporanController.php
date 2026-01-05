<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Builders\LaporanPengukuranBuilder;
use App\Factories\ReportFactory;
use App\Models\Pengukuran;
use App\Models\Balita;

class LaporanController extends Controller
{
    // Tampilkan form + hasil laporan
    public function index(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $pengukuran = collect();

        if ($tanggalMulai && $tanggalSelesai) {
            $pengukuran = (new LaporanPengukuranBuilder())
                ->betweenTanggal($tanggalMulai, $tanggalSelesai)
                ->orderByTanggal()
                ->get();
        }

        $report = ReportFactory::make('html');

        return $report->render([
            'pengukuran'     => $pengukuran,
            'tanggalMulai'   => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }

    // Cetak PDF
    public function cetak(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $pengukuran = (new LaporanPengukuranBuilder())
            ->betweenTanggal($tanggalMulai, $tanggalSelesai)
            ->orderByTanggal()
            ->get();

        // Factory Method
        $report = ReportFactory::make('pdf');

        return $report->render([
            'pengukuran'     => $pengukuran,
            'tanggalMulai'   => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);
    }
}
