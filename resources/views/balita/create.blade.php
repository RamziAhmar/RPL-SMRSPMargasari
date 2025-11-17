<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Data Balita
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('balita.store') }}">
                @csrf

                {{-- Nama Balita --}}
                <div>
                    <x-input-label for="nama" value="Nama Balita" />
                    <x-text-input id="nama" name="nama" type="text"
                                  class="mt-1 block w-full"
                                  :value="old('nama')" required autofocus />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                {{-- Tanggal Lahir --}}
                <div class="mt-4">
                    <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('tanggal_lahir')" required />
                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                </div>

                {{-- Jenis Kelamin --}}
                <div class="mt-4">
                    <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                    <select id="jenis_kelamin" name="jenis_kelamin"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin')=='L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                </div>

                {{-- Nama Ibu --}}
                <div class="mt-4">
                    <x-input-label for="nama_ibu" value="Nama Ibu" />
                    <x-text-input id="nama_ibu" name="nama_ibu" type="text"
                                  class="mt-1 block w-full"
                                  :value="old('nama_ibu')" />
                    <x-input-error :messages="$errors->get('nama_ibu')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>
                        Simpan
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
