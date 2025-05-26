@props(['lessons', 'sortBy', 'sortOrder', 'page', 'params'])

<h3 class="admin__section__title">Посещаемость</h3>

@if (Auth::user()->student)
    <x-form-search target='/student/attendance/' sortBy="{{ $sortBy }}" sortOrder="{{ $sortOrder }}">
        <input type="search" name="query" placeholder="Поиск по всем полям" value="{{ request('query') }}">

        <input type="search" name="searchEmployeeLastName" placeholder="Фамилия"
            value="{{ request('searchEmployeeLastName') }}">
        <input type="search" name="searchEmployeeFirstName" placeholder="Имя"
            value="{{ request('searchEmployeeFirstName') }}">
        <input type="search" name="searchLessonName" placeholder="Название пары"
            value="{{ request('searchLessonName') }}">
        <input type="search" name="searchPlace" placeholder="Место проведения" value="{{ request('searchPlace') }}">
    </x-form-search>
@endif

<div class="admin__section__content">
    <table class="table lessons__table">
        <thead>
            <tr>
                @if (Auth::user()->student && request()->is('student/attendance'))
                    <th><x-sortBy target='/student/attendance/' name="employee_id" sortBy="employee_id"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}"
                            :params="$params">Преподаватель</x-sortBy></th>
                    <th><x-sortBy target='/student/attendance/' name="lesson_name" sortBy="lesson_name"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Название
                            пары</x-sortBy></th>
                    <th><x-sortBy target='/student/attendance/' name="classroom" sortBy="classroom"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Место
                            проведения</x-sortBy></th>
                    <th><x-sortBy target='/student/attendance/' name="created_at" sortBy="created_at"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Время
                            отметки</x-sortBy></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($lessons as $lesson)
                <tr>
                    @if (Auth::user()->student && request()->is('student/attendance'))
                        <th>{{ $lesson->employee->last_name . ' ' . $lesson->employee->first_name }}</th>
                    @endif
                    <th>{{ $lesson->lesson_name }}</th>
                    <th>{{ $lesson->classroom }}</th>
                    <th>{{ date('d-m-Y, H:i', strtotime($lesson->created_at)) }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
