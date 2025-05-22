<x-layout>
    <div class="container">
        <div class="qr__wrapper">
            <div class="qr__code">
                {!! QrCode::size(300)->generate(url('/lesson/confirm/' . str_replace('/', '_', openssl_encrypt($user->id . '_' . $date, "AES-128-ECB", 'password')))) !!}
            </div>
        </div>
    </div>
</x-layout>