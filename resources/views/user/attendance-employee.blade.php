@if (Auth::user()->admin)
    <x-admin>
        <x-attendance-content-employee :lessons="$lessons" :sortBy="$sortBy" :sortOrder="$sortOrder" :page="$page"
            :params="$params" />
    </x-admin>
@else
    <x-users-attendance>
        <x-attendance-content-employee :lessons="$lessons" :sortBy="$sortBy" :sortOrder="$sortOrder" :page="$page"
            :params="$params" />
    </x-users-attendance>
@endif
