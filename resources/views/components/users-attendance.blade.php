<x-layout>
    <x-slot:heading>Посещаемость</x-slot:heading>
    <div class="container__admin">
        <section class="admin">
            <div class="admin__wrapper">
                {{-- @if (Auth::user()->admin) --}}
                {{-- <aside class="admin__aside">
                    <ul>
                        <li><a href="/employee/attendance">Просмотр посещаемости</a></li>
                    </ul>
                </aside> --}}
                <section class="admin__section">
                    {{ $slot }}
                </section>
            </div>
        </section>
    </div>
</x-layout>