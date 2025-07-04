@if (Auth::user()->isAdmin())
    <x-admin>
        <x-attendance-content-employee :lessons="$lessons" :sortBy="$sortBy" :sortOrder="$sortOrder" :page="$page"
            :params="$params" :lessons1="$lessons1" />
    </x-admin>
@else
    <x-users-attendance>
        <x-attendance-content-employee :lessons="$lessons" :sortBy="$sortBy" :sortOrder="$sortOrder" :page="$page"
            :params="$params" :lessons1="$lessons1" />
    </x-users-attendance>
@endif
