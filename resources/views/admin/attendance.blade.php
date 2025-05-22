<x-admin>
    <h3 class="admin__section__title">Посещаемость</h3>
    <form action="{{ url('/admin/attendance') }}" method="GET">
        <div class="search">
            <input type="search" name="query" placeholder="Поиск пользователей..." value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary">Найти</button>
        </div>
    </form>
    <div class="admin__section__content">
        <table class="table lessons__table">
            <thead>
                <tr>
                    <th>Преподаватель</th>
                    <th>Студент</th>
                    <th>Название пары</th>
                    <th>Место проведения</th>
                    <th>Время отметки</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lessons as $lesson)
                    <tr>
                        <th>{{ $lesson->employee->last_name . ' ' . $lesson->employee->first_name }}</th>
                        <th>{{ $lesson->student->last_name . ' ' . $lesson->student->first_name }}</th>
                        <th>{{ $lesson->lesson_name }}</th>
                        <th>{{ $lesson->classroom }}</th>
                        <th>{{ date('d-m-Y, H:i', strtotime($lesson->created_at)) }}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin>
