<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Balita
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('pengukuran.save', $pengukuran->id_ukur) }}">
                @csrf
                @method('PUT')

                {{-- Tanggal Ukur --}}
                <div>
                    <x-input-label for="tanggal_ukur" value="Tanggal Ukur" />
                    <x-text-input id="tanggal_ukur" name="tanggal_ukur" type="date" class="mt-1 block w-full" :value="old('tanggal_ukur', $pengukuran->tanggal_ukur)"
                        required />
                    <x-input-error :messages="$errors->get('tanggal_ukur')" class="mt-2" />
                </div>

                {{-- Berat Badan --}}
                <div>
                    <x-input-label for="bb_kg" value="Berat Badan (kg)" />
                    <x-text-input id="bb_kg" name="bb_kg" type="text" class="mt-1 block w-full" :value="old('bb_kg', $pengukuran->bb_kg)"
                        required autofocus />
                    <x-input-error :messages="$errors->get('bb_kg')" class="mt-2" />
                </div>

                {{-- Tinggi Badan (cm) --}}
                <div class="mt-4">
                    <x-input-label for="tb_cm" value="Tinggi Badan (cm)" />
                    <x-text-input id="tb_cm" name="tb_cm" type="text" class="mt-1 block w-full"
                        :value="old('tb_cm', $pengukuran->tb_cm)" required />
                    <x-input-error :messages="$errors->get('tb_cm')" class="mt-2" />
                </div>

                {{-- Lingkar Kepala (cm) --}}
                <div class="mt-4">
                    <x-input-label for="lila_cm" value="Lingkar Kepala (cm)" />
                    <x-text-input id="lila_cm" name="lila_cm" type="text" class="mt-1 block w-full"
                        :value="old('lila_cm', $pengukuran->lila_cm)" required />
                    <x-input-error :messages="$errors->get('lila_cm')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <x-back-button :href="route('pengukuran.index', $pengukuran->id_balita)" />
                    <x-primary-button>
                        Update
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
