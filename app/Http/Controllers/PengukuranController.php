<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\User;
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
            ->orderBy('tanggal_ukur', 'desc')
            ->get();

        $latest = $pengukurans->first();
        $simulasi = null;

        if ($latest) {

            $prev = $pengukurans->skip(1)->first();

            $delta = 0.5;

            if ($prev) {
                $delta = $latest->tb_cm - $prev->tb_cm;
            }

            try {
                $response = Http::timeout(5)->post('http://127.0.0.1:5000/simulasi', [
                    'umur' => intval($latest->umur_bulan),
                    'tinggi' => floatval($latest->tb_cm),
                    'delta_tinggi' => floatval($delta),
                    'gender' => $balita->jenis_kelamin
                ]);

                // DEBUG 
                // dd($response->json());

                if ($response->successful()) {

                    $data = $response->json();

                    // cek kalau API return error
                    if (isset($data['error'])) {
                        \Log::error("API returned error: " . $data['error']);
                        $simulasi = null;
                    } else {
                        $simulasi = $data;
                    }
                } else {
                    \Log::error("API gagal: " . $response->status());
                }
            } catch (\Exception $e) {
                \Log::error("API error: " . $e->getMessage());
            }
        }

        return view('pengukuran.index', compact(
            'balita',
            'pengukurans',
            'simulasi'
        ));
    }

    public function create(Balita $balita)
    {
        return view('pengukuran.create', compact('balita'));
    }

    public function store(Request $request, Balita $balita)
    {
        $validated = $request->validate([
            'bb_kg'   => 'required|numeric',
            'tb_cm'   => 'required|numeric',
            'lila_cm' => 'nullable|numeric',
        ]);

        $tanggalUkur = Carbon::now()->toDateString();

        $umurBulan = (int) round(
            Carbon::parse($balita->tanggal_lahir)
                ->diffInMonths(Carbon::parse($tanggalUkur))
        );

        // =========================
        // HITUNG DELTA TINGGI
        // =========================
        $last = Pengukuran::where('id_balita', $balita->id_balita)
            ->latest('tanggal_ukur')
            ->first();

        if ($last) {
            $deltaTinggi = $validated['tb_cm'] - $last->tb_cm;
        } else {
            $deltaTinggi = 0.5; // default awal
        }

        // =========================
        // HIT API PYTHON
        // =========================
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'umur' => $umurBulan,
            'tinggi' => $validated['tb_cm'],
            'delta_tinggi' => $deltaTinggi,
            'gender' => $balita->jenis_kelamin == 'L' ? 'L' : 'P'
        ]);

        // dd($response->body());

        if (!$response->successful()) {
            return back()->with('error', 'Gagal menghubungi API AI');
        }

        $result = $response->json();


        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        $statusStunting = $result['status_stunting'];

        // =========================
        // SIMPAN DATA
        // =========================
        $pengukuran = Pengukuran::create([
            'id_balita'    => $balita->id_balita,
            'id_user'      => Auth::id(),
            'tanggal_ukur' => $tanggalUkur,
            'umur_bulan'   => $umurBulan,
            'bb_kg'        => $validated['bb_kg'],
            'tb_cm'        => $validated['tb_cm'],
            'lila_cm'      => $validated['lila_cm'] ?? null,
            'status_stunting' => $statusStunting,
        ]);

        return redirect()
            ->route('balita.index', $balita->id_balita)
            ->with('success', 'Pengukuran & analisis WHO berhasil disimpan');
    }

    public function grafikData(Balita $balita)
    {
        $pengukurans = $balita->pengukurans()
            ->orderBy('tanggal_ukur', 'asc')
            ->get(['tanggal_ukur', 'bb_kg', 'tb_cm']);

        return response()->json($pengukurans);
    }

    public function edit($id)
    {
        $pengukuran = Pengukuran::findOrFail($id);

        $balita = Balita::all();
        $user = User::all();

        return view('pengukuran.edit', compact('pengukuran', 'balita', 'user'));
    }

    public function save(Request $request, $id)
    {
        $pengukuran = Pengukuran::findOrFail($id);


        $request->validate([
            'bb_kg' => 'required|numeric',
            'tb_cm' => 'required|numeric',
            'lila_cm' => 'required|numeric',
        ]);

        $response = Http::post('http://127.0.0.1:5000/predict', [
            'umur' => $pengukuran->umur_bulan,
            'tinggi' => $pengukuran->tb_cm,
            'delta_tinggi' => 0.5,
            'gender' => $pengukuran->balita->jenis_kelamin == 'L' ? 'L' : 'P'
        ]);

        // dd($response->body());

        $pengukuran->update([
            'id_balita' => $pengukuran->id_balita,
            'id_user' => $pengukuran->id_user,
            'tanggal_ukur' => $pengukuran->tanggal_ukur,
            'umur_bulan' => $pengukuran->umur_bulan,
            'bb_kg' => $request->bb_kg,
            'tb_cm' => $request->tb_cm,
            'lila_cm' => $request->lila_cm,
            'status_stunting' => $response->json()['status_stunting'],
            'updated_at' => now(),
        ]);

        return redirect()->route('pengukuran.index', $pengukuran->id_balita)
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengukuran = Pengukuran::findOrFail($id);
        $pengukuran->delete();

        return redirect()->route('pengukuran.index', $pengukuran->id_balita)
            ->with('success', 'Data berhasil dihapus');
    }
}
