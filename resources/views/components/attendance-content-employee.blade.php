@props(['lessons', 'sortBy', 'sortOrder', 'page', 'params', 'lessons1'])

<h3 class="admin__section__title">Посещаемость</h3>

<x-form-search target='/employee/attendance/' sortBy="{{ $sortBy }}" sortOrder="{{ $sortOrder }}">
    <input type="search" name="query" placeholder="Поиск по всем полям" value="{{ request('query') }}">

    <input type="search" name="searchStudentLastName" placeholder="Фамилия"
        value="{{ request('searchStudentLastName') }}">
    <input type="search" name="searchStudentFirstName" placeholder="Имя"
        value="{{ request('searchStudentFirstName') }}">
    <input type="search" name="searchGroup" placeholder="Группа" value="{{ request('searchGroup') }}">
    <input type="search" name="searchLessonName" placeholder="Название пары" value="{{ request('searchLessonName') }}">
    <input type="search" name="searchPlace" placeholder="Место проведения" value="{{ request('searchPlace') }}">
</x-form-search>

<div class="admin__section__content">
    <table class="table lessons__table">
        <thead>
            <tr>
                <th><x-sortBy target='/employee/attendance/' name="student_id" sortBy="student_id"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Студент</x-sortBy>
                </th>
                <th><x-sortBy target='/employee/attendance/' name="group" sortBy="group"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Группа</x-sortBy>
                </th>
                <th><x-sortBy target='/employee/attendance/' name="lesson_name" sortBy="lesson_name"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Название
                        пары</x-sortBy></th>
                <th><x-sortBy target='/employee/attendance/' name="classroom" sortBy="classroom"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Место
                        проведения</x-sortBy></th>
                <th><x-sortBy target='/employee/attendance/' name="created_at" sortBy="created_at"
                        sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Время
                        отметки</x-sortBy></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lessons as $lesson)
                <tr>
                    <th>{{ $lesson->student->last_name . ' ' . $lesson->student->first_name }}</th>
                    <th>{{ $lesson->student->group }}</th>
                    <th>{{ $lesson->lesson_name }}</th>
                    <th>{{ $lesson->classroom }}</th>
                    <th>{{ date('d-m-Y, H:i', strtotime($lesson->created_at)) }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="desktop__paginate" style="margin-top: 10px;">
        {{ $lessons1->paginate(20)->onEachSide(1) }}
    </div>

    <div class="mobile__paginate" style="margin-top: 10px;">
        {{ $lessons1->simplePaginate() }}
    </div>
</div>
