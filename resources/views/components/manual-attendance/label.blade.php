@props(['name'])

<label for="{{ $name }}"
    style="margin-bottom: 0.3rem; color:rgb(130, 130, 130); margin-bottom:12px; min-width:250px; text-align: center;">
    {{ $slot }}
</label>
