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
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama balita / nama ibu..."
                    class="w-full md:w-64 border-gray-300 rounded-md shadow-sm"
                />
                <x-primary-button class="whitespace-nowrap">
                    Cari
                </x-primary-button>
                @if($search)
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

        @if(session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-4">

            @if($search)
                <p class="mb-2 text-sm text-gray-600">
                    Menampilkan hasil pencarian untuk:
                    <span class="font-semibold">"{{ $search }}"</span>
                </p>
            @endif

            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left">Nama</th>
                        <th class="py-2 text-left">Tanggal Lahir</th>
                        <th class="py-2 text-left">JK</th>
                        <th class="py-2 text-left">Nama Ibu</th>
                        <th class="py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balitas as $b)
                        <tr class="border-b">
                            <td class="py-2">{{ $b->nama }}</td>
                            <td class="py-2">{{ $b->tanggal_lahir }}</td>
                            <td class="py-2">{{ $b->jenis_kelamin }}</td>
                            <td class="py-2">{{ $b->nama_ibu }}</td>
                            <td class="py-2">
                                <a href="{{ route('pengukuran.index', $b->id_balita) }}" class="text-blue-600">
                                    Pengukuran
                                </a> |
                                <a href="{{ route('balita.edit', $b) }}" class="text-yellow-600">
                                    Edit
                                </a>
                                <form action="{{ route('balita.destroy', $b) }}"
                                      method="POST" class="inline"
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
                                @if($search)
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
