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

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-3">Simulasi Risiko Stunting</h5>

                @if (!empty($simulasi))
                    <canvas id="chartSimulasi"></canvas>

                    <div class="mt-3">
                        <strong>Umur Risiko Stunting:</strong><br>

                    </div>
                @else
                    <p class="text-muted">Data simulasi belum tersedia</p>
                @endif
            </div>
        </div>

        <div class="mb-4">
            <div class="w-full rounded-md px-4 py-3 font-semibold text-sm sm:text-base text-center shadow bg-gray-200 text-gray-800 border border-gray-300">
                Normal: {{ $simulasi['normal']['umur_stunting'] ?? 'Aman' }} <br>
                Membaik: {{ $simulasi['baik']['umur_stunting'] ?? 'Aman' }} <br>
                Memburuk: {{ $simulasi['buruk']['umur_stunting'] ?? 'Aman' }}
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
                        <th class="py-2 text-left">Status Stunting</th>
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
                            @if ($p->status_stunting == 0)
                                <td class="py-2 text-green-600 font-semibold">Aman</td>
                            @else
                                <td class="py-2 text-red-600 font-semibold">Stunting</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if (isset($simulasi))
    <script>
        const normal = @json($simulasi['normal']['data']);
        const baik = @json($simulasi['baik']['data']);
        const buruk = @json($simulasi['buruk']['data']);

        const labelsSimulasi = normal.map(d => d.umur);

        const dataNormal = normal.map(d => d.z_score);
        const dataBaik = baik.map(d => d.z_score);
        const dataBuruk = buruk.map(d => d.z_score);

        const ctxSimulasi = document.getElementById('chartSimulasi');

        new Chart(ctxSimulasi, {
            type: 'line',
            data: {
                labels: labelsSimulasi,
                datasets: [{
                        label: 'Normal (ML)',
                        data: dataNormal,
                        borderColor: 'blue',
                        fill: false
                    },
                    {
                        label: 'Membaik',
                        data: dataBaik,
                        borderColor: 'green',
                        fill: false
                    },
                    {
                        label: 'Memburuk',
                        data: dataBuruk,
                        borderColor: 'red',
                        fill: false
                    },
                    {
                        label: 'Batas Stunting (-2)',
                        data: labelsSimulasi.map(() => -2),
                        borderDash: [5, 5],
                        borderColor: 'black',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endif

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
    const labelsPertumbuhan = pengukuranData.map(p => p.umur_bulan);
    const ctxPertumbuhan = document.getElementById('grafikPertumbuhan');

    let chart = new Chart(ctxPertumbuhan, {
        type: 'bar',
        data: {
            labels: labelsPertumbuhan,
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
    document.getElementById('metricSelect').addEventListener('change', function() {
        const selected = this.value;

        chart.data.datasets = [datasetsMap[selected]];
        chart.update();
    });
</script>
