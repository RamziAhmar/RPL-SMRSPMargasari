<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengukuran;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $status         = $request->input('status');
        $nama           = $request->input('nama');

        $query = Pengukuran::with('balita');

        // Filter tanggal
        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
        }

        // Filter status
        if ($status !== null && $status !== '') {
            $query->where('status_stunting', $status);
        }

        // Filter nama balita
        if ($nama) {
            $query->whereHas('balita', function ($q) use ($nama) {
                $q->where('nama', 'like', '%' . $nama . '%');
            });
        }

        $pengukuran = $query->orderBy('created_at', 'desc')->get();

        return view('laporan.index', compact(
            'pengukuran',
            'tanggalMulai',
            'tanggalSelesai',
            'status',
            'nama'
        ));
    }

    public function cetak(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $status         = $request->input('status');
        $nama           = $request->input('nama');

        $query = Pengukuran::with('balita');

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($status !== null && $status !== '') {
            $query->where('status_stunting', $status);
        }

        if ($nama) {
            $query->whereHas('balita', function ($q) use ($nama) {
                $q->where('nama', 'like', '%' . $nama . '%');
            });
        }

        $pengukuran = $query->orderBy('created_at', 'desc')->get();

        // langsung pakai view PDF (misalnya pakai dompdf)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', [
            'pengukuran'     => $pengukuran,
            'tanggalMulai'   => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);

        return $pdf->download('laporan.pdf');
    }
}
