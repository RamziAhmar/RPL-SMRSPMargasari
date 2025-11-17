<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nama" value="Nama" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)"
                        required autofocus />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="username" value="Username" />
                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                        :value="old('username', $user->username)" required />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="role" value="Role" />
                    <select id="role" name="role"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="kader" {{ old('role', $user->role) == 'kader' ? 'selected' : '' }}>Kader</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Password (kosongkan jika tidak diubah)" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Update</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
