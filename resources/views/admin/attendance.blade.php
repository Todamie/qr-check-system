<x-admin>
    <h3 class="admin__section__title">Посещаемость</h3>

    <x-form-search target='/admin/attendance/' sortBy="{{ $sortBy }}" sortOrder="{{ $sortOrder }}">
        <input type="search" name="query" placeholder="Поиск по всем полям" value="{{ request('query') }}">
        <input type="search" name="searchEmployeeLastName" placeholder="Фамилия преподавателя"
            value="{{ request('searchEmployeeLastName') }}">
        <input type="search" name="searchEmployeeFirstName" placeholder="Имя преподавателя"
            value="{{ request('searchEmployeeFirstName') }}">

        <input type="search" name="searchStudentLastName" placeholder="Фамилия студента"
            value="{{ request('searchStudentLastName') }}">
        <input type="search" name="searchStudentFirstName" placeholder="Имя студента"
            value="{{ request('searchStudentFirstName') }}">

        <input type="search" name="searchLessonName" placeholder="Название пары"
            value="{{ request('searchLessonName') }}">
        <input type="search" name="searchGroup" placeholder="Группа" value="{{ request('searchGroup') }}">
        <input type="search" name="searchPlace" placeholder="Место проведения"
            value="{{ request('searchPlace') }}">
    </x-form-search>

    <p class="user__counter">Всего отметок в системе: <span style="font-weight: 600;">{{ $lessonCount }}</span>
    </p>
    <br>
    <div class="admin__section__content">
        <table class="table lessons__table">
            <thead>
                <tr>
                    <th><x-sortBy target='/admin/attendance/' name="employee_id" sortBy="employee_id"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Преподаватель</x-sortBy></th>
                    <th><x-sortBy target='/admin/attendance/' name="student_id" sortBy="student_id"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Студент</x-sortBy></th>
                    <th><x-sortBy target='/admin/attendance/' name="group" sortBy="group"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Группа</x-sortBy></th>
                    <th><x-sortBy target='/admin/attendance/' name="lesson_name" sortBy="lesson_name"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Название пары</x-sortBy></th>
                    <th><x-sortBy target='/admin/attendance/' name="classroom" sortBy="classroom"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Место проведения</x-sortBy></th>
                    <th><x-sortBy target='/admin/attendance/' name="created_at" sortBy="created_at"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Время отметки</x-sortBy></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lessons as $lesson)
                    <tr>
                        <th>{{ $lesson->employee->last_name . ' ' . $lesson->employee->first_name }}</th>
                        <th>{{ $lesson->student->last_name . ' ' . $lesson->student->first_name }}</th>
                        @if ($lesson->student->group)
                            <th>{{ $lesson->student->group }}</th>
                        @else
                            <th>Нет группы</th>
                        @endif
                        <th>{{ $lesson->lesson_name }}</th>
                        <th>{{ $lesson->classroom }}</th>
                        <th>{{ date('d-m-Y, H:i', strtotime($lesson->created_at)) }}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="desktop__paginate" style="margin-top: 10px;">
        {{ $lessons1->paginate(20)->onEachSide(1) }}
    </div>

    <div class="mobile__paginate" style="margin-top: 10px;">
        {{ $lessons1->simplePaginate() }}
    </div>

</x-admin>
