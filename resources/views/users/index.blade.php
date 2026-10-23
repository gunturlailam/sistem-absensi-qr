<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar User</h3>

                    <a href="{{ route('users.create') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Tambah User
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">QR</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Role</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">QR Code</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    @if ($user->qr_code)
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(48)->generate($user->qr_code) !!}
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-900">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-700">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="rounded px-2 py-1 text-xs font-semibold
                                            {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                    {{ $user->qr_code }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="rounded bg-yellow-500 px-3 py-1 text-sm text-white hover:bg-yellow-600">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada user.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>