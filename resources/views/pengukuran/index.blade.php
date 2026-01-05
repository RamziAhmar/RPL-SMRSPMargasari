<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengukuran {{ $balita->nama }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4 flex items-center justify-end">

            <x-back-button :href="route('balita.index')" />
        </div>

        @php
            // ambil pengukuran terakhir (paling baru) beserta hasilPrediksi-nya
            $latestMeasurement = $pengukurans->sortByDesc('tanggal_ukur')->first();
            $latestPred = $latestMeasurement?->hasilPrediksi;

            $baseClass = 'w-full rounded-md px-4 py-3 font-semibold text-sm sm:text-base text-center shadow';
            $bannerClass = 'bg-gray-200 text-gray-800 border border-gray-300';
            $labelText = 'Hasil prediksi bulan depan: belum tersedia';
            $detailText = '';

            if ($latestPred) {
                if ($latestPred->label_pred) {
                    // prediksi akan stunting → MERAH
                    $bannerClass = 'bg-red-500 text-white border border-red-600';
                    $labelText = 'Hasil prediksi bulan depan: RISIKO STUNTING';
                } else {
                    // aman → HIJAU
                    $bannerClass = 'bg-green-500 text-white border border-green-600';
                    $labelText = 'Hasil prediksi bulan depan: AMAN';
                }

                $percent = number_format($latestPred->prob_pred * 100, 2);
                $detailText = " (Probabilitas: {$percent}%)";
            }
        @endphp

        <div class="mb-4">
            <div class="{{ $baseClass . ' ' . $bannerClass }}">
                {{ $labelText }}{!! $detailText !!}
            </div>
        </div>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg mb-2">
            <label class="block text-sm font-medium mb-1">
                Pilih Jenis Grafik
            </label>
            <select id="metricSelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="tb_cm">Tinggi Badan (cm)</option>
                <option value="bb_kg">Berat Badan (kg)</option>
                <option value="lila_cm">LILA (cm)</option>
            </select>
        </div>

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
                    @foreach ($pengukurans as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->tanggal_ukur }}</td>
                            <td class="py-2">{{ $p->umur_bulan }}</td>
                            <td class="py-2">{{ $p->bb_kg }}</td>
                            <td class="py-2">{{ $p->tb_cm }}</td>
                            <td class="py-2">{{ $p->lila_cm }}</td>
                            <td class="py-2">
                                @if ($p->hasilPrediksi)
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

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const pengukuranData = @json($pengukurans);

    const labels = pengukuranData.map(p => p.umur_bulan);

    const datasetsMap = {
        tb_cm: {
            label: 'Tinggi Badan (cm)',
            data: pengukuranData.map(p => p.tb_cm),
            backgroundColor: '#3B82F6'
        },
        bb_kg: {
            label: 'Berat Badan (kg)',
            data: pengukuranData.map(p => p.bb_kg),
            backgroundColor: '#22C55E'
        },
        lila_cm: {
            label: 'LILA (cm)',
            data: pengukuranData.map(p => p.lila_cm),
            backgroundColor: '#F59E0B'
        }
    };
</script>

<script>
    const ctx = document.getElementById('grafikPertumbuhan');

    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [datasetsMap.tb_cm] // default TB
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    document.getElementById('metricSelect').addEventListener('change', function () {
        const selected = this.value;

        chart.data.datasets = [datasetsMap[selected]];
        chart.update();
    });
</script>
