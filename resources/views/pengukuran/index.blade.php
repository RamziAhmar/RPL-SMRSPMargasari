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

        <div class="bg-white p-5 shadow-sm sm:rounded-xl mb-6 border border-gray-100">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Data Balita
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-semibold text-gray-800">
                        {{ $balita->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal Lahir</p>
                    <p class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($balita->tanggal_lahir)->format('d M Y') . ' (' . floor(\Carbon\Carbon::parse($balita->tanggal_lahir)->diffInDays(now()) / 30) . ' bulan)' }} 
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Jenis Kelamin</p>
                    <p class="font-semibold text-gray-800">
                        {{ $balita->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Nama Ibu</p>
                    <p class="font-semibold text-gray-800">
                        {{ $balita->nama_ibu }}
                    </p>
                </div>

            </div>
        </div>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg mb-6">
            @if (!empty($simulasi))
                <h3 class="text-lg font-semibold mb-3 text-gray-700">
                    Simulasi Pertumbuhan
                </h3>

                <div style="height:300px;">
                    <canvas id="chartSimulasi"></canvas>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4 text-sm font-semibold">
                    <div
                        class="p-3 rounded-lg text-center
                {{ ($simulasi['normal']['umur_stunting'] ?? 'Aman') == 'Aman' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        Normal <br>
                        {{ $simulasi['normal']['umur_stunting'] ?? 'Aman' }}
                    </div>

                    <div
                        class="p-3 rounded-lg text-center
                {{ ($simulasi['baik']['umur_stunting'] ?? 'Aman') == 'Aman' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        Membaik <br>
                        {{ $simulasi['baik']['umur_stunting'] ?? 'Aman' }}
                    </div>

                    <div
                        class="p-3 rounded-lg text-center
                {{ ($simulasi['buruk']['umur_stunting'] ?? 'Aman') == 'Aman' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        Memburuk <br>
                        {{ $simulasi['buruk']['umur_stunting'] ?? 'Aman' }}
                    </div>
                </div>
            @else
                <p class="text-muted">Data simulasi belum tersedia</p>
            @endif
        </div>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg mb-6">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">
                Grafik Pertumbuhan
            </h3>

            <label class="block text-sm font-medium mb-2">
                Pilih Jenis Grafik
            </label>

            <div class="flex bg-gray-100 p-1 rounded-xl w-fit mb-4">
                <button class="metric-btn active-blue" data-value="tb_cm" data-color="blue">
                    Tinggi Badan
                </button>

                <button class="metric-btn" data-value="bb_kg" data-color="green">
                    Berat Badan
                </button>

                <button class="metric-btn" data-value="lila_cm" data-color="orange">
                    LILA
                </button>
            </div>

            <div style="height:300px;">
                <canvas id="grafikPertumbuhan"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">
                Tabel Pertumbuhan
            </h3>
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
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        pointRadius: 3,
                        fill: true
                    },
                    {
                        label: 'Membaik',
                        data: dataBaik,
                        borderColor: '#22C55E',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        tension: 0.4,
                        pointRadius: 3,
                        fill: true
                    },
                    {
                        label: 'Memburuk',
                        data: dataBuruk,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        tension: 0.4,
                        pointRadius: 3,
                        fill: true
                    },
                    {
                        label: 'Batas Stunting (-2)',
                        data: labelsSimulasi.map(() => -2),
                        borderColor: '#000',
                        borderDash: [6, 6],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Z-Score'
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Umur (bulan)'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endif

<script>
    const pengukuranData = @json($pengukurans).sort((a, b) => a.umur_bulan - b.umur_bulan);
    const labels = pengukuranData.map(p => p.umur_bulan);

    const datasetsMap = {
        tb_cm: {
            label: 'Tinggi Badan (cm)',
            data: pengukuranData.map(p => p.tb_cm),
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            tension: 0.4,
            pointRadius: 4,
            fill: true
        },
        bb_kg: {
            label: 'Berat Badan (kg)',
            data: pengukuranData.map(p => p.bb_kg),
            borderColor: '#22C55E',
            backgroundColor: 'rgba(34,197,94,0.1)',
            tension: 0.4,
            pointRadius: 4,
            fill: true
        },
        lila_cm: {
            label: 'LILA (cm)',
            data: pengukuranData.map(p => p.lila_cm),
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245,158,11,0.1)',
            tension: 0.4,
            pointRadius: 4,
            fill: true
        }
    };
</script>

<script>
    const labelsPertumbuhan = pengukuranData.map(p => p.umur_bulan);
    const ctxPertumbuhan = document.getElementById('grafikPertumbuhan');

    let chart = new Chart(ctxPertumbuhan, {
        type: 'line',
        data: {
            labels: labelsPertumbuhan,
            datasets: [datasetsMap.tb_cm]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Umur (bulan)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Nilai'
                    }
                }
            }
        }
    });
</script>

<script>
    document.querySelectorAll('.metric-btn').forEach(button => {
        button.addEventListener('click', function() {

            const value = this.dataset.value;
            const color = this.dataset.color;

            // update chart
            chart.data.datasets = [datasetsMap[value]];
            chart.update();

            // reset semua button
            document.querySelectorAll('.metric-btn').forEach(btn => {
                btn.classList.remove(
                    'active-blue',
                    'active-green',
                    'active-orange'
                );
            });

            // aktifkan sesuai warna
            this.classList.add(`active-${color}`);
        });
    });
</script>
