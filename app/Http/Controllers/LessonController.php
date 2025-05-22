<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class LessonController extends Controller
{
    public function confirm($employee_id)
    {
        $decrypted = openssl_decrypt(str_replace("_", "/", $employee_id), "AES-128-ECB", 'password');
        $employee_id = explode("_", $decrypted)[0];
        $qr_date = explode("_", $decrypted)[1];

        $current_date = Carbon::now()->startOfDay()->timestamp;
        if ($current_date != $qr_date) {
            abort(403);
        }

        if (! Auth::check()) {
            return redirect('/login');
        }

        $employee = User::find($employee_id);
        if (! $employee) {
            return redirect('/')->with('error', 'Преподаватель не найден');
        }

        $user = Auth::user();

        $current_lesson = $this->current_lesson($user->group);

        return view('lesson-confirmation', [
            'employee' => $employee,
            'user' => $user,
            'current_lesson' => $current_lesson
        ]);
    }

    public function store($teacher_id)
    {
        $current_lesson = json_decode(request()->input('current_lesson'), true);

        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Вы должны войти в систему');
        }

        $student = Auth::user();

        $teacher = User::find($teacher_id);
        if (!$teacher) {
            return redirect('/')->with('error', 'Преподаватель не найден');
        }

        try {
            Lesson::create([
                'employee_id' => $teacher->id,
                'student_id' => $student->id,
                'lesson_name' => $current_lesson['предмет'],
                'classroom' => $current_lesson['код_аудитории'] . ' (' . $current_lesson['код_корпуса'] . ')',
                'created_at' => strtotime(now()) + 60 * 60 * 5,
                'updated_at' => strtotime(now()) + 60 * 60 * 5,
            ]);

            return redirect('/')->with('success', 'Посещение успешно   отмечено');
        } catch (\Exception $e) {
            Log::error('Ошибка при сохранении посещения: ' . $e->getMessage());
            return redirect('/')->with('error', 'Ошибка при сохранении');
        }
    }

    public function index(Request $request)
    {
        // Получаем значение из строки запроса
        $query = $request->input('query');

        // Если есть запрос, фильтруем пользователей
        $lessons = Lesson::when($query, function ($q) use ($query) {
            return $q->whereHas('employee', function ($q) use ($query) {
                $q->where('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
                ->orWhereHas('student', function ($q) use ($query) {
                    $q->where('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                ->orWhere('classroom', 'LIKE', "%{$query}%");
        })->paginate(20);

        return view('admin.attendance', compact('lessons'));
    }

    public function index_employee(Request $request)
    {
        // Получаем значение из строки запроса
        $query = $request->input('query');

        $user = Auth::user()->id;

        // Если есть запрос, фильтруем пользователей
        $lessons = Lesson::where('employee_id', $user)
            ->when($query, function ($q) use ($query) {
                return $q->WhereHas('student', function ($q) use ($query) {
                    $q->where('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                })
                    ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                    ->orWhere('classroom', 'LIKE', "%{$query}%");
            })->paginate(20);

        return view('user.attendance', compact('lessons'));
    }

    public function index_student(Request $request)
    {
        // Получаем значение из строки запроса
        $query = $request->input('query');

        $user = Auth::user()->id;

        // Если есть запрос, фильтруем пользователей
        $lessons = Lesson::where('student_id', $user)
            ->when($query, function ($q) use ($query) {
                return $q->WhereHas('employee', function ($q) use ($query) {
                    $q->where('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%");
                })
                    ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                    ->orWhere('classroom', 'LIKE', "%{$query}%");
            })->paginate(20);

        return view('user.attendance', compact('lessons'));
    }

    public function current_lesson($group)
    {
        $client = new Client();
        $response = $client->get("http://192.168.16.105:86/", [
            'query' => [
                'group' => $group
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $now = Carbon::now()->timezone(env('APP_TIMEZONE'));

        $current_week = $this->isEvenWeek($now);
        $currentTime = $now->format('H:i');

        // Текущий день недели
        $currentDay = $now->locale('ru')->isoFormat('dddd');

        function isTimeInLesson($currentTime, $lessonTime)
        {
            list($startTime, $endTime) = explode(' - ', $lessonTime);
            return $currentTime >= $startTime && $currentTime <= $endTime;
        }

        $found = false;
        foreach ($data as $lesson) {
            if (
                mb_strtolower($lesson['код_дня']) == $currentDay &&
                isTimeInLesson($currentTime, $lesson['код_пары']) &&
                $current_week == $lesson['код_недели']
            ) {
                $found = true;
                return $lesson;
            }
        }

        if (!$found) {
            abort(403);
        }
    }


    function isEvenWeek($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        $baseDate = Carbon::create(2024, 9, 2);

        $weeksDiff = $date->diffInWeeks($baseDate);

        if ($date->lt($baseDate)) {
            return null;
        }

        if ($weeksDiff % 2 === 0) {
            return 2;
        } else {
            return 1;
        }
    }
}
