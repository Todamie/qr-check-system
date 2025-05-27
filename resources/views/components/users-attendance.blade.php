<x-layout>
    <x-slot:heading>Посещаемость</x-slot:heading>
    <div class="container__admin">
        <section class="admin">
            <div class="admin__wrapper">
                <section class="admin__section">
                    {{ $slot }}
                </section>
            </div>
        </section>
    </div>
</x-layout>