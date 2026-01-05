<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- CARD TOTAL --}}
            <div class="bg-white p-6 shadow rounded">
                Selamat datang di Sistem Monitoring Risiko Stunting.
                <div class="mt-4">
                    Total Balita terdaftar:
                    <strong>{{ $totalBalita }}</strong>
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


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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




</x-app-layout>
