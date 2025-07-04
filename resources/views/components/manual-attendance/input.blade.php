@props(['name', 'title', 'placeholder', 'type'])



{{-- <div style="display: flex; flex-direction: column; align-items: center">

    <x-manual-attendance.label :name="$name">{{ $title }}</x-manual-attendance.label>

    <input type="{{ $type }}" name="{{ $name }}" style='width:100%;' placeholder="{{ $placeholder }}" required>
</div> --}}


<div style="display: flex; flex-direction: column; align-items: center">

    <x-manual-attendance.label :name="$name">{{ $title }}</x-manual-attendance.label>

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="input" style="width:100%; border-bottom: 2px solid rgb(230, 230, 230); padding: .2rem" placeholder="{{ $placeholder }}" required>
</div>
