@props(['target', 'placeholder', 'name', 'sortBy', 'sortOrder'])

<form action="{{ url($target) }}" method="GET" class="search">
        {{ $slot }}
        <input type="hidden" name="sortBy" value="{{ request('sortBy', $sortBy) }}">
        <input type="hidden" name="sortOrder" value="{{ request('sortOrder', $sortOrder) }}">
        <input type="hidden" name="page" value="1">
        <button type="submit" class="btn btn-primary">Найти</button>
</form>
