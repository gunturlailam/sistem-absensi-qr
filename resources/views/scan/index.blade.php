<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 loading-tight">
            Scan Absensi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div id="scan-result" class="hidden rounded px-4 py-3 text-center font-semibold"></div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Arahkan QR Code ke Kamera</h3>
                <div id="reader"></div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Arahkan QR Code ke Kamera</h3>
                <div id="reader"></div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Atau Input Kode Manual</h3>
                <form id="manual-form" class="flex gap-2">
                    <input type="text" id="manual-code" placeholder="Tempel kode QR di sini" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                        Proses
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
    <script>
        const CSRF = "{{ csrf_token() }}";
        const PROCESS_URL = "{{ route('scan.process') }}";

        let scanner = null;
        let processing = false;

        function showResult(success, message) {
            const box = document.getElementById('scan-result');
            box.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            box.classList.add(
                success ? 'bg-green-100' : 'bg-red-100',
                success ? 'text-green-800' : 'text-red-800'
            );
            box.textContent = message;
        }

        function submitCode(code) {
            if (processing) return;
            processing = true;

            if (scanner) {
                scanner.clear().catch(() => {});
                scanner = null;
            }

            fetch(PROCESS_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({
                        code: code
                    }),
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        showResult(res.ok && data.success, data.message);
                    });
                })
                .catch(function() {
                    showResult(false, 'Terjadi kesalahan jaringan.');
                })
                .finally(function() {
                    document.getElementById('manual-code').value = '';
                    setTimeout(function() {
                        processing = false;
                        startScanner();
                    }, 4000);
                });
        }

        function startScanner() {
            document.getElementById('reader').innerHTML = '';
            scanner = new Html5QrcodeScanner('reader', {
                fps: 10,
                qrbox: 250
            }, false);
            scanner.render(onScanSuccess, function() {});
        }

        function onScanSuccess(decodedText) {
            submitCode(decodedText);
        }

        document.getElementById('manual-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const code = document.getElementById('manual-code').value.trim();
            if (code) submitCode(code);
        })

        startScanner();
    </script>
</x-app-layout>