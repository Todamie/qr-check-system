@if (Auth::user()->admin)
    <x-admin>
        <x-attendance-content :lessons="$lessons" />
    </x-admin>
@else
    <x-users-attendance>
        <x-attendance-content :lessons="$lessons" />
    </x-users-attendance>
@endif