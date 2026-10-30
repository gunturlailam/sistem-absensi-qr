<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Scan Absensi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('scan_success'))
            <div class="rounded bg-green-100 px-4 py-3 text-center font-semibold text-green-800">
                {{ session('scan_success') }}
            </div>
            @endif

            @if (session('scan_error'))
            <div class="rounded bg-red-100 px-4 py-3 text-center font-semibold text-red-800">
                {{ session('scan_error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Arahkan QR Code ke Kamera</h3>
                <div id="reader"></div>
                <p id="camera-note" class="mt-2 text-sm text-gray-500">Menyiapkan kamera...</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Atau Input Kode Manual</h3>

                <form id="scan-form" method="POST" action="{{ route('scan.process') }}" class="flex gap-2">
                    @csrf
                    <input type="text" id="code" name="code" placeholder="Tempel kode QR di sini"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Proses
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            if (typeof window.Html5QrcodeScanner === 'undefined') {
                document.getElementById('camera-note').textContent =
                    'Library scanner belum termuat. Pastikan npm run dev jalan, lalu refresh.';
                return;
            }

            document.getElementById('camera-note').textContent = '';

            var scanner = new window.Html5QrcodeScanner('reader', {
                fps: 10,
                qrbox: 250
            }, false);
            scanner.render(function(decodedText) {
                document.getElementById('code').value = decodedText;
                document.getElementById('scan-form').submit();
            }, function() {});
        });
    </script>
</x-app-layout>