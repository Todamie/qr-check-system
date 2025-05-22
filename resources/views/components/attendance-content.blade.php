@props(['lessons'])

<h3 class="admin__section__title">Посещаемость</h3>
<form action="{{ url('/employee/attendance') }}" method="GET">
    <div class="search">
        <input type="search" name="query" placeholder="Поиск пользователей..." value="{{ request('query') }}">
        <button type="submit" class="btn btn-primary">Найти</button>
    </div>
</form>
<div class="admin__section__content">
    <table class="table lessons__table">
        <thead>
            <tr>
                @if (Auth::user()->employee)
                    <th>Студент</th>
                    <th>Группа</th>
                @elseif (Auth::user()->student)
                    <th>Преподаватель</th>
                @endif
                <th>Название пары</th>
                <th>Место проведения</th>
                <th>Время отметки</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lessons as $lesson)
                <tr>
                    @if (Auth::user()->employee)
                        <th>{{ $lesson->student->last_name . ' ' . $lesson->student->first_name }}</th>
                        <th>{{ $lesson->student->group }}</th>
                    @elseif (Auth::user()->student)
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
