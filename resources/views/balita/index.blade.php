<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Balita
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- BARIS PENCARIAN --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <form method="GET" action="{{ route('balita.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" name="q" value="{{ $search ?? '' }}"
                    placeholder="Cari nama balita / nama ibu..."
                    class="w-full md:w-64 border-gray-300 rounded-md shadow-sm" />
                <x-primary-button class="whitespace-nowrap">
                    Cari
                </x-primary-button>
                @if ($search)
                    <a href="{{ route('balita.index') }}" class="text-sm text-gray-600 underline">
                        Reset
                    </a>
                @endif
            </form>

            <a href="{{ route('balita.create') }}"
                class="inline-block px-4 py-2 bg-blue-600 text-white rounded text-center">
                + Tambah Balita
            </a>
        </div>
        {{-- END BARIS PENCARIAN --}}

        @if (session('success'))
            <div id="success-alert"
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>

                <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-4">

            @if ($search)
                <p class="mb-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk:
                    <span class="font-semibold">"{{ $search }}"</span>
                </p>
            @endif

            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left">Nama</th>
                        <th class="py-2 text-left">Umur (bln)</th>
                        <th class="py-2 text-left">Tanggal Lahir</th>
                        <th class="py-2 text-left">JK</th>
                        <th class="py-2 text-left">Nama Ibu</th>
                        <th class="py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balitas as $b)
                        @php
                            $umurBulan = floor(\Carbon\Carbon::parse($b->tanggal_lahir)->diffInDays(now()) / 30);
                        @endphp
                        <tr class="border-b">
                            <td class="py-2">{{ $b->nama }}</td>
                            <td class="py-2">{{ $umurBulan }}</td>
                            <td class="py-2">{{ $b->tanggal_lahir }}</td>
                            <td class="py-2">{{ $b->jenis_kelamin }}</td>
                            <td class="py-2">{{ $b->nama_ibu }}</td>
                            <td class="py-2">
                                <a href="{{ route('pengukuran.create', $b->id_balita) }}" class="text-blue-600">
                                    Pengukuran
                                </a> |
                                <a href="{{ route('pengukuran.index', $b->id_balita) }}" class="text-blue-600">
                                    Detail
                                </a> |
                                <a href="{{ route('balita.edit', $b) }}" class="text-yellow-600">
                                    Edit
                                </a>
                                <form action="{{ route('balita.destroy', $b) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus data?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                Tidak ada data balita
                                @if ($search)
                                    untuk kata kunci "<strong>{{ $search }}</strong>"
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $balitas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
