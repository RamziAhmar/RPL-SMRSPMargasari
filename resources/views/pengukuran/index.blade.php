<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengukuran {{ $balita->nama }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <a href="{{ route('pengukuran.create', $balita->id_balita) }}"
           class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">
            + Tambah Pengukuran
        </a>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg mb-6">
            <canvas id="grafikPertumbuhan" height="120"></canvas>
        </div>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left">Tanggal</th>
                        <th class="py-2 text-left">Umur (bulan)</th>
                        <th class="py-2 text-left">BB (kg)</th>
                        <th class="py-2 text-left">TB (cm)</th>
                        <th class="py-2 text-left">LILA (cm)</th>
                        <th class="py-2 text-left">Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengukurans as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->tanggal_ukur }}</td>
                            <td class="py-2">{{ $p->umur_bulan }}</td>
                            <td class="py-2">{{ $p->bb_kg }}</td>
                            <td class="py-2">{{ $p->tb_cm }}</td>
                            <td class="py-2">{{ $p->lila_cm }}</td>
                            <td class="py-2">
                                @if($p->hasilPrediksi)
                                    {{ $p->hasilPrediksi->label_pred ? 'Risiko Stunting' : 'Tidak' }}
                                    ({{ number_format($p->hasilPrediksi->prob_pred * 100, 2) }}%)
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikPertumbuhan').getContext('2d');

        fetch("{{ route('pengukuran.grafik', $balita->id_balita) }}")
            .then(res => res.json())
            .then(data => {
                const labels = data.map(d => d.tanggal_ukur);
                const bb = data.map(d => d.bb_kg);
                const tb = data.map(d => d.tb_cm);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            { label: 'BB (kg)', data: bb, borderWidth: 2 },
                            { label: 'TB (cm)', data: tb, borderWidth: 2 },
                        ]
                    }
                });
            });
    </script>
    @endpush
</x-app-layout>
