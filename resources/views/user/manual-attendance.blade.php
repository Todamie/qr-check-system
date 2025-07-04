@if (Auth::user()->isAdmin())
<x-admin>
    <x-attendance-manual :lessons_list="$lessons_list" :students="$students" :employees="$employees" />
</x-admin>
@else
<x-users-attendance>
    <x-attendance-manual :lessons_list="$lessons_list" :students="$students" :employees="$employees" />
</x-users-attendance>
@endif