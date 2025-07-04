@props(['target', 'placeholder'])

<form action="{{ url($target) }}" method="POST" class="search">
        @csrf
        {{ $slot }}
        <button type="submit" class="btn btn-primary" style="margin-left:4px; height: 50%;align-self:flex-end">Добавить</button>
</form>
