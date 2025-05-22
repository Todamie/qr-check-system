<x-layout>
    <div class="scanner">
        <video id="qr-video"></video>
        <script type="module">
            import QrScanner from 'https://unpkg.com/qr-scanner/qr-scanner.min.js';

            const videoElem = document.getElementById('qr-video');
            // const resultElem = document.getElementById('qr-result');

            const qrScanner = new QrScanner(
                videoElem,
                result => {
                    // При успешном сканировании
                    // resultElem.textContent = 'Сканирование успешно!';
                    qrScanner.stop();

                    // Просто перенаправляем на страницу подтверждения
                    window.location.href = result.data;
                },
                {
                    highlightScanRegion: true,
                    //     highlightCodeOutline: true,
                }
            );
            // Запускаем сканер при загрузке страницы
            qrScanner.start();
        </script>
    </div>
</x-layout>