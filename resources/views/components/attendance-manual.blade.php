@props(['lessons_list', 'students', 'employees'])

<h3 class="admin__section__title">Ручная отметка о посещаемости</h3>

<x-messages :success="session('success')" :error="session('error')" />

<x-manual-attendance.form-add-attendance target='/employee/manual-attendance/'>

    <x-manual-attendance.select name='student_id' title='Выберите студента'>
        @foreach ($students as $student)
            <option value="{{ $student->id }}">{{ $student->last_name . ' ' . $student->first_name }}</option>
        @endforeach
    </x-manual-attendance.select>

    <x-manual-attendance.select name='employee_id' title='Выберите преподавателя'>
        @foreach ($employees as $employee)
            <option value="{{ $employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }}</option>
        @endforeach
    </x-manual-attendance.select>

    <x-manual-attendance.select name='lesson_name' title='Выберите предмет'>
        @foreach ($lessons_list as $lesson)
            <option value="{{ $lesson }}">{{ $lesson }}</option>
        @endforeach
    </x-manual-attendance.select>

    <x-manual-attendance.input name="created_at" title='Время отметки' placeholder="" type='datetime-local' />
    <x-manual-attendance.input name="classroom" title='Место проведения пары' placeholder="100(Корпус 7)" type='search' />

</x-manual-attendance.form-add-attendance>