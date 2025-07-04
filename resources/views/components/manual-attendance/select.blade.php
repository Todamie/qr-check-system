@props(['name', 'title'])

<div style="display: flex; flex-direction: column; align-items: center">

    <x-manual-attendance.label :name="$name">{{ $title }}</x-manual-attendance.label>

    <select name="{{ $name }}" id='{{ $name }}' style="border-bottom: 2px solid rgb(230, 230, 230); width:100%; padding-bottom: 8px;">
        {{ $slot }}
    </select>
</div>
