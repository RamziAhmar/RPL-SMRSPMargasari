<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function dashboard()
    {
        $totalBalita = Balita::count();
        return view('dashboard', compact('totalBalita'));
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
