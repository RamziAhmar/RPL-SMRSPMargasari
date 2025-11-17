<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola User Sistem
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('users.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">
            + Tambah User
        </a>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-red-600">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left">Nama</th>
                        <th class="py-2 text-left">Username</th>
                        <th class="py-2 text-left">Role</th>
                        <th class="py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr class="border-b">
                            <td class="py-2">{{ $u->nama }}</td>
                            <td class="py-2">{{ $u->username }}</td>
                            <td class="py-2">{{ $u->role }}</td>
                            <td class="py-2">
                                <a href="{{ route('users.edit', $u->id) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
