<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Pengukuran
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-4 mb-4">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                        class="border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                        class="border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="border rounded px-7 py-2">
                        <option value="">Semua</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Stunting</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Aman</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Balita</label>
                    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..."
                        class="border rounded px-3 py-2">
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Tampilkan
                    </button>
                    @if ($pengukuran->count())
                        <a href="{{ route('laporan.cetak', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded">
                            Cetak / Download
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($pengukuran->count())
            <div class="bg-white shadow rounded p-4">
                <h3 class="font-semibold mb-2">
                    Rekap Pengukuran ({{ $tanggalMulai }} s/d {{ $tanggalSelesai }})
                </h3>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-2 py-1 text-center w-1/6">Tanggal</th>
                            <th class="border px-2 py-1 text-center">Nama Balita</th>
                            <th class="border py-1 text-center">JK</th>
                            <th class="border px-2 py-1 text-center">TB (cm)</th>
                            <th class="border px-2 py-1 text-center">BB (kg)</th>
                            <th class="border px-2 py-1 text-center">LILA (cm)</th>
                            <th class="border px-2 py-1 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengukuran as $p)
                            <tr>
                                <td class="border px-2 py-1">{{ $p->created_at }}</td>
                                <td class="border px-2 py-1">{{ $p->balita->nama }}</td>
                                <td class="border px-2 py-1 text-center">{{ $p->balita->jenis_kelamin }}</td>
                                <td class="border px-2 py-1 text-center">{{ $p->tb_cm }}</td>
                                <td class="border px-2 py-1 text-center">{{ $p->bb_kg }}</td>
                                <td class="border px-2 py-1 text-center">{{ $p->lila_cm }}</td>
                                <td class="border px-2 py-1 text-center">
                                    @if ($p->status_stunting)
                                        <span class="text-red-600 font-semibold">Stunting</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
