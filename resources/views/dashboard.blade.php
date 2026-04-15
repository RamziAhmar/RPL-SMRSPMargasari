<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- OVERVIEW CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Total Balita --}}
                <div class="bg-white p-5 shadow rounded border-l-4 border-blue-500 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Total Balita</div>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $totalBalita }}
                        </div>
                    </div>
                    <i class="fas fa-baby text-3xl text-blue-500"></i>
                </div>

                {{-- Total Pengukuran --}}
                <div class="bg-white p-5 shadow rounded border-l-4 border-purple-500 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Total Pengukuran</div>
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $totalPengukuran }}
                        </div>
                    </div>
                    <i class="fas fa-ruler-combined text-3xl text-purple-500"></i>
                </div>

                {{-- Jumlah Stunting --}}
                <div class="bg-white p-5 shadow rounded border-l-4 border-red-500 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Balita Stunting</div>
                        <div class="text-2xl font-bold text-red-600">
                            {{ $stunting }}
                        </div>
                    </div>
                    <i class="fas fa-triangle-exclamation text-3xl text-red-500"></i>
                </div>

                {{-- Persentase Stunting --}}
                <div
                    class="bg-white p-5 shadow rounded border-l-4 border-{{ $warnaStunting = $persenStunting > 20 ? 'red' : 'green' }}-500 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Persentase Stunting</div>
                        <div
                            class="text-2xl font-bold text-{{ $warnaStunting = $persenStunting > 20 ? 'red' : 'green' }}-600">
                            {{ $persenStunting }}%
                        </div>
                    </div>
                    <i
                        class="fas fa-chart-line text-3xl text-{{ $warnaStunting = $persenStunting > 20 ? 'red' : 'green' }}-500"></i>
                </div>

            </div>

            {{-- GRAFIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- JENIS KELAMIN --}}
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="font-semibold mb-3 text-center">
                        Distribusi Jenis Kelamin Balita
                    </h3>

                    <div class="flex items-center gap-6 justify-center">
                        {{-- Chart --}}
                        <div class="w-48 h-48">
                            <canvas id="genderChart"></canvas>
                        </div>

                        {{-- Text --}}
                        <div class="text-sm space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span>Laki-laki:</span>
                                <strong>{{ $jumlahLaki }}</strong>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 bg-pink-500 rounded-full"></span>
                                <span>Perempuan:</span>
                                <strong>{{ $jumlahPerempuan }}</strong>
                            </div>
                            <div class="mt-2 font-semibold">
                                Total: {{ $jumlahLaki + $jumlahPerempuan }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STATUS STUNTING --}}
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="font-semibold mb-3 text-center">
                        Distribusi Status Stunting
                    </h3>

                    <div class="flex items-center gap-6 justify-center">
                        {{-- Chart --}}
                        <div class="w-48 h-48">
                            <canvas id="stuntingChart"></canvas>
                        </div>

                        {{-- Text --}}
                        <div class="text-sm space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span>
                                <span>Stunting:</span>
                                <strong>{{ $stunting }}</strong>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span>
                                <span>Tidak Stunting:</span>
                                <strong>{{ $tidakStunting }}</strong>
                            </div>
                            <div class="mt-2 font-semibold">
                                Total: {{ $stunting + $tidakStunting }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- GRAFIK PERTUMBUHAN BAYI BARU LAHIR --}}
            <div class="bg-white p-6 shadow rounded">
                <h3 class="text-center font-semibold mb-4">
                    Rata-rata Berat & Tinggi Bayi Usia 0–1 Bulan
                </h3>

                <div class="max-w-xl mx-auto">
                    <canvas id="babyChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- STUNTING PER BULAN --}}
                <div class="bg-white p-6 shadow rounded">
                    <h3 class="text-center font-semibold mb-4">
                        Jumlah Stunting per Bulan
                    </h3>
                    <div class="h-64">
                        <canvas id="stuntingBulanan"></canvas>
                    </div>
                </div>

                {{-- STUNTING PER TAHUN --}}
                <div class="bg-white p-6 shadow rounded">
                    <h3 class="text-center font-semibold mb-4">
                        Jumlah Stunting per Tahun
                    </h3>
                    <div class="h-64">
                        <canvas id="stuntingTahunan"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const labelsBaby = {!! json_encode($grafikBayiBaruLahir->pluck('bulan')) !!};
            const avgBBBaby = {!! json_encode($grafikBayiBaruLahir->pluck('avg_bb')) !!};
            const avgTBBaby = {!! json_encode($grafikBayiBaruLahir->pluck('avg_tb')) !!};

            new Chart(document.getElementById('babyChart'), {
                type: 'line',
                data: {
                    labels: labelsBaby,
                    datasets: [{
                            label: 'Berat Bayi (kg)',
                            data: avgBBBaby,
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Tinggi Bayi (cm)',
                            data: avgTBBaby,
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        </script>

        <script>
            new Chart(document.getElementById('genderChart'), {
                type: 'pie',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [{{ $jumlahLaki }}, {{ $jumlahPerempuan }}]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // penting!
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>

        <script>
            new Chart(document.getElementById('stuntingChart'), {
                type: 'pie',
                data: {
                    labels: ['Stunting', 'Tidak Stunting'],
                    datasets: [{
                        data: [{{ $stunting }}, {{ $tidakStunting }}],
                        backgroundColor: [
                            '#EF4444', // Merah = Stunting
                            '#22C55E' // Hijau = Aman
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>

        <script>
            const bulanLabels = {!! json_encode($stuntingPerBulan->pluck('bulan')) !!};
            const bulanData = {!! json_encode($stuntingPerBulan->pluck('jumlah')) !!};

            new Chart(document.getElementById('stuntingBulanan'), {
                type: 'bar',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Jumlah Stunting',
                        data: bulanData,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>

        <script>
            const tahunLabels = {!! json_encode($stuntingPerTahun->pluck('tahun')) !!};
            const tahunData = {!! json_encode($stuntingPerTahun->pluck('jumlah')) !!};

            new Chart(document.getElementById('stuntingTahunan'), {
                type: 'bar',
                data: {
                    labels: tahunLabels,
                    datasets: [{
                        label: 'Jumlah Stunting',
                        data: tahunData,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>
</x-app-layout>
