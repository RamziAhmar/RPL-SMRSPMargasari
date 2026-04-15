<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalitaController extends Controller
{
    public function dashboard()
    {
        $totalBalita = Balita::count();
        $totalPengukuran = Pengukuran::count();
        $stunting = Pengukuran::where('status_stunting', true)->count();
        $tidakStunting = Pengukuran::where('status_stunting', false)->count();
        $jumlahLaki = Balita::where('jenis_kelamin', 'L')->count();
        $jumlahPerempuan = Balita::where('jenis_kelamin', 'P')->count();

        $persenStunting = $totalPengukuran > 0
            ? round(($stunting / $totalPengukuran) * 100, 1)
            : 0;

        $grafikBayiBaruLahir = DB::table('pengukuran')
            ->select(
                DB::raw('DATE_FORMAT(tanggal_ukur, "%Y-%m") as bulan'),
                DB::raw('AVG(bb_kg) as avg_bb'),
                DB::raw('AVG(tb_cm) as avg_tb')
            )
            ->where('umur_bulan', '<=', 1)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $stuntingPerBulan = DB::table('pengukuran')
            ->select(
                DB::raw('DATE_FORMAT(tanggal_ukur, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->where('status_stunting', 1)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $stuntingPerTahun = DB::table('pengukuran')
            ->select(
                DB::raw('YEAR(tanggal_ukur) as tahun'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->where('status_stunting', 1)
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        return view('dashboard', compact(
            'totalBalita',
            'jumlahLaki',
            'jumlahPerempuan',
            'stunting',
            'tidakStunting',
            'persenStunting',
            'totalPengukuran',
            'grafikBayiBaruLahir',
            'stuntingPerBulan',
            'stuntingPerTahun'
        ));
    }

    public function index(Request $request)
    {
        $search = $request->input('q'); // nama parameter pencarian

        $query = Balita::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nama_ibu', 'like', '%' . $search . '%');
            });
        }

        $balitas = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('balita.index', compact('balitas', 'search'));
    }

    public function create()
    {
        return view('balita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu'      => 'nullable|string|max:255',
        ]);

        Balita::create($validated);

        return redirect()->route('balita.index')->with('success', 'Data balita berhasil disimpan');
    }

    public function edit(Balita $balita)
    {
        return view('balita.edit', compact('balita'));
    }

    public function update(Request $request, Balita $balita)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu'      => 'nullable|string|max:255',
        ]);

        $balita->update($validated);

        return redirect()->route('balita.index')->with('success', 'Data balita berhasil diupdate');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('balita.index')->with('success', 'Data balita berhasil dihapus');
    }
}
