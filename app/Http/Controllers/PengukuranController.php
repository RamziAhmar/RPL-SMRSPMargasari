<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\HasilPrediksi;
use App\Services\PredictionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PengukuranController extends Controller
{
    public function index(Balita $balita)
    {
        $pengukurans = $balita->pengukurans()
            ->with('hasilPrediksi')
            ->orderBy('tanggal_ukur', 'asc')
            ->get();

        // // NANTI (contoh): ambil hasil prediksi paling terbaru (diasumsikan untuk bulan depan)
        // $latestPrediction = $balita->pengukurans()
        //     ->with('hasilPrediksi')
        //     ->whereDate('tanggal_ukur', '<=', now())
        //     ->orderByDesc('tanggal_ukur')
        //     ->first()?->hasilPrediksi;
        //
        // return view('pengukuran.index', compact('balita', 'pengukurans', 'latestPrediction'));

        return view('pengukuran.index', compact('balita', 'pengukurans'));
    }

    public function create(Balita $balita)
    {
        return view('pengukuran.create', compact('balita'));
    }

    public function store(Request $request, Balita $balita)
    {
        $validated = $request->validate([
            'bb_kg'        => 'required|numeric',
            'tb_cm'        => 'required|numeric',
            'lila_cm'      => 'nullable|numeric',
        ]);

        $tanggalUkur = Carbon::now()->toDateString();
        $umurBulan = Carbon::parse($balita->tanggal_lahir)
            ->diffInMonths(Carbon::parse($tanggalUkur));

        $pengukuran = Pengukuran::create([
            'id_balita'    => $balita->id_balita,
            'id_user'      => Auth::id(),
            'tanggal_ukur' => $tanggalUkur,
            'umur_bulan'   => $umurBulan,
            'bb_kg'        => $validated['bb_kg'],
            'tb_cm'        => $validated['tb_cm'],
            'lila_cm'      => $validated['lila_cm'] ?? null,
            'status_stunting' => null, // bisa diisi nanti atau dari API
        ]);

        // === PANGGIL API DENGAN SINGLETON ===
        $client = PredictionClient::getInstance();

        $data = $client->predict([
            'umur_bulan'    => $umurBulan,
            'bb_kg'         => $pengukuran->bb_kg,
            'tb_cm'         => $pengukuran->tb_cm,
            'lila_cm'       => $pengukuran->lila_cm,
            'jenis_kelamin' => $balita->jenis_kelamin,
        ]);

        if (!empty($data)) {
            HasilPrediksi::create([
                'id_ukur'    => $pengukuran->id_ukur,
                'label_pred' => $data['label_pred'] ?? false,
                'prob_pred'  => $data['prob_pred'] ?? 0,
            ]);

            // sinkronkan status_stunting
            $pengukuran->update([
                'status_stunting' => $data['label_pred'] ?? null,
            ]);
        }


        return redirect()
            ->route('balita.index', $balita->id_balita)
            ->with('success', 'Pengukuran dan prediksi berhasil disimpan');
    }

    public function grafikData(Balita $balita)
    {
        $pengukurans = $balita->pengukurans()
            ->orderBy('tanggal_ukur', 'asc')
            ->get(['tanggal_ukur', 'bb_kg', 'tb_cm']);

        return response()->json($pengukurans);
    }
}
