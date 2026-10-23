<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4">
                    <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-800">
                        &larr; Kembali
                    </a>
                </div>

                @if (session('error'))
                <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Role" />

                        <select id="role"
                            name="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="user" @selected(old('role', $user->role) == 'user')>User</option>
                            <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                        </select>

                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password Baru (Opsional)" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">
                            Kosongkan jika password tidak ingin diubah.
                        </p>
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    @if ($user->qr_code)
                    <div>
                        <x-input-label value="QR Code" />

                        <div class="mt-2 flex items-center gap-4">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->generate($user->qr_code) !!}

                            <div class="text-sm text-gray-600">
                                <p class="font-semibold">Kode QR:</p>
                                <p class="font-mono break-all">{{ $user->qr_code }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="rounded bg-yellow-100 px-4 py-3 text-yellow-800">
                        QR code belum tersedia. QR code akan dibuat otomatis setelah user diupdate.
                    </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>