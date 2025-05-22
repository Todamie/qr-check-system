<x-layout>
    <div class="confirmation">
        <form method="post" action="{{ url('/lesson/mark/' . $employee->id) }}" class="confirmation__form">
            @csrf
            <h2 class="form__title">Подтвердить посещение занятия</h2>
            <p style="text-align: center; color: gray; margin-bottom: .5rem;">Преподаватель: <br><span>{{ $employee->first_name . ' ' . $employee->last_name }}</span></p>
            <p style="text-align: center; color: gray; margin-bottom: .5rem;">Название пары: <br><span>{{ $current_lesson['предмет'] }}</span></p>
            <p style="text-align: center; color: gray; margin-bottom: .5rem;">Аудитория: <br><span>{{ $current_lesson['код_аудитории'] . " (". $current_lesson['код_корпуса'] .")" }}</span></p>
            <input type="hidden" name="current_lesson" value="{{ json_encode($current_lesson) }}">
            <button type="submit" class="btn btn-primary">Подтвердить</button>
        </form>
    </div>
</x-layout>