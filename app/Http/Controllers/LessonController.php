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
        $query = trim($request->input('query')) ?? '';
        $sortBy = trim($request->input('sortBy'));
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $sortOrder = trim($request->input('sortOrder'));
        if (empty($sortOrder)) {
            $sortOrder = 'desc';
        }
        $page = trim($request->input('page')) ?? 1;
        $searchStudentLastName = trim($request->input('searchStudentLastName')) ?? '';
        $searchStudentFirstName = trim($request->input('searchStudentFirstName')) ?? '';
        $searchEmployeeLastName = trim($request->input('searchEmployeeLastName')) ?? '';
        $searchEmployeeFirstName = trim($request->input('searchEmployeeFirstName')) ?? '';
        $searchLessonName = trim($request->input('searchLessonName')) ?? '';
        $searchGroup = trim($request->input('searchGroup')) ?? '';
        $searchPlace = trim($request->input('searchPlace')) ?? '';

        $searchWithParams = Lesson::with(['employee', 'student']);

        if (!empty($searchStudentLastName)) {
            $searchWithParams->whereHas('student', function ($q) use ($searchStudentLastName) {
                $q->where('last_name', 'LIKE', "%{$searchStudentLastName}%");
            });
        }
        if (!empty($searchStudentFirstName)) {
            $searchWithParams->whereHas('student', function ($q) use ($searchStudentFirstName) {
                $q->where('first_name', 'LIKE', "%{$searchStudentFirstName}%");
            });
        }
        if (!empty($searchEmployeeLastName)) {
            $searchWithParams->whereHas('employee', function ($q) use ($searchEmployeeLastName) {
                $q->where('last_name', 'LIKE', "%{$searchEmployeeLastName}%");
            });
        }
        if (!empty($searchEmployeeFirstName)) {
            $searchWithParams->whereHas('employee', function ($q) use ($searchEmployeeFirstName) {
                $q->where('first_name', 'LIKE', "%{$searchEmployeeFirstName}%");
            });
        }
        if (!empty($searchGroup)) {
            $searchWithParams->where(function ($q) use ($searchGroup) {
                $q->whereHas('student', function ($q2) use ($searchGroup) {
                    $q2->where('group', 'LIKE', "%{$searchGroup}%");
                })->orWhereHas('employee', function ($q2) use ($searchGroup) {
                    $q2->where('group', 'LIKE', "%{$searchGroup}%");
                });
            });
        }
        if (!empty($searchLessonName)) {
            $searchWithParams->where('lesson_name', 'LIKE', "%{$searchLessonName}%");
        }
        if (!empty($searchPlace)) {
            $searchWithParams->where('classroom', 'LIKE', "%{$searchPlace}%");
        }

        if (!empty($query)) {
            $searchWithParams->where(function ($q) use ($query) {
                $q->whereHas('employee', function ($q2) use ($query) {
                    $q2->where('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('group', 'LIKE', "%{$query}%");
                })
                    ->orWhereHas('student', function ($q2) use ($query) {
                        $q2->where('last_name', 'LIKE', "%{$query}%")
                            ->orWhere('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('group', 'LIKE', "%{$query}%");
                    })
                    ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                    ->orWhere('classroom', 'LIKE', "%{$query}%");
            });
        }

        if ($sortBy === 'employee_id') {
            $lessons1 = $searchWithParams->join('users as u1', 'lessons.employee_id', '=', 'u1.id')
                ->orderBy('u1.last_name', $sortOrder)
                ->select('lessons.*');
        } elseif ($sortBy === 'student_id') {
            $lessons1 = $searchWithParams->join('users as u2', 'lessons.student_id', '=', 'u2.id')
                ->orderBy('u2.last_name', $sortOrder)
                ->select('lessons.*');
        } elseif ($sortBy === 'group') {
            $lessons1 = $searchWithParams->join('users as u3', 'lessons.student_id', '=', 'u3.id')
                ->orderBy('u3.group', $sortOrder)
                ->select('lessons.*');
        } else {
            $lessons1 = $searchWithParams->orderBy($sortBy, $sortOrder);
        }

        $lessons = $lessons1->paginate();

        $lessonCount = Lesson::count();

        $params = [
            'query' => $query,
            'searchStudentLastName' => $searchStudentLastName,
            'searchStudentFirstName' => $searchStudentFirstName,
            'searchEmployeeLastName' => $searchEmployeeLastName,
            'searchEmployeeFirstName' => $searchEmployeeFirstName,
            'searchLessonName' => $searchLessonName,
            'searchGroup' => $searchGroup,
            'searchPlace' => $searchPlace,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page
        ];

        return view('admin.attendance', [
            'lessons' => $lessons,
            'lessonCount' => $lessonCount,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page,
            'params' => $params,
            'lessons1' => $lessons1
        ]);
    }

    public function index_employee(Request $request)
    {
        // Получаем значение из строки запроса
        $query = trim($request->input('query')) ?? '';
        $sortBy = trim($request->input('sortBy'));
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $sortOrder = trim($request->input('sortOrder'));
        if (empty($sortOrder)) {
            $sortOrder = 'desc';
        }
        $page = trim($request->input('page')) ?? 1;
        $searchStudentLastName = trim($request->input('searchStudentLastName')) ?? '';
        $searchStudentFirstName = trim($request->input('searchStudentFirstName')) ?? '';
        $searchLessonName = trim($request->input('searchLessonName')) ?? '';
        $searchGroup = trim($request->input('searchGroup')) ?? '';
        $searchPlace = trim($request->input('searchPlace')) ?? '';

        $searchWithParams = Lesson::with(['employee', 'student']);

        if (!empty($searchStudentLastName)) {
            $searchWithParams->whereHas('student', function ($q) use ($searchStudentLastName) {
                $q->where('last_name', 'LIKE', "%{$searchStudentLastName}%");
            });
        }
        if (!empty($searchStudentFirstName)) {
            $searchWithParams->whereHas('student', function ($q) use ($searchStudentFirstName) {
                $q->where('first_name', 'LIKE', "%{$searchStudentFirstName}%");
            });
        }
        if (!empty($searchGroup)) {
            $searchWithParams->whereHas('student', function ($q) use ($searchGroup) {
                $q->where('group', 'LIKE', "%{$searchGroup}%");
            });
        }
        if (!empty($searchLessonName)) {
            $searchWithParams->where('lesson_name', 'LIKE', "%{$searchLessonName}%");
        }
        if (!empty($searchPlace)) {
            $searchWithParams->where('classroom', 'LIKE', "%{$searchPlace}%");
        }

        if (!empty($query)) {
            $searchWithParams->whereHas('student', function ($q) use ($query) {
                $q->where('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('group', 'LIKE', "%{$query}%");
            })
                ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                ->orWhere('classroom', 'LIKE', "%{$query}%");
        }

        if ($sortBy === 'student_id') {
            $lessons1 = $searchWithParams->join('users as u1', 'lessons.student_id', '=', 'u1.id')
                ->where('employee_id', Auth::user()->id)
                ->orderBy('u1.last_name', $sortOrder)
                ->select('lessons.*');
        } elseif ($sortBy === 'group') {
            $lessons1 = $searchWithParams->join('users as u2', 'lessons.student_id', '=', 'u2.id')
                ->where('employee_id', Auth::user()->id)
                ->orderBy('u2.group', $sortOrder)
                ->select('lessons.*');
        } else {
            $lessons1 = $searchWithParams->where('employee_id', Auth::user()->id)
                ->orderBy($sortBy, $sortOrder);
        }

        $lessons = $lessons1->paginate();

        $lessonCount = Lesson::count();

        $params = [
            'query' => $query,
            'searchStudentLastName' => $searchStudentLastName,
            'searchStudentFirstName' => $searchStudentFirstName,
            'searchLessonName' => $searchLessonName,
            'searchGroup' => $searchGroup,
            'searchPlace' => $searchPlace,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page
        ];

        return view('user.attendance-employee', [
            'lessons' => $lessons,
            'lessonCount' => $lessonCount,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page,
            'params' => $params,
            'lessons1' => $lessons1
        ]);
    }

    public function index_student(Request $request)
    {
        // Получаем значение из строки запроса
        $query = trim($request->input('query')) ?? '';
        $sortBy = trim($request->input('sortBy'));
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $sortOrder = trim($request->input('sortOrder'));
        if (empty($sortOrder)) {
            $sortOrder = 'desc';
        }
        $page = trim($request->input('page')) ?? 1;
        $searchEmployeeLastName = trim($request->input('searchEmployeeLastName')) ?? '';
        $searchEmployeeFirstName = trim($request->input('searchEmployeeFirstName')) ?? '';
        $searchLessonName = trim($request->input('searchLessonName')) ?? '';
        $searchPlace = trim($request->input('searchPlace')) ?? '';

        $searchWithParams = Lesson::with(['employee', 'student']);

        if (!empty($searchEmployeeLastName)) {
            $searchWithParams->whereHas('employee', function ($q) use ($searchEmployeeLastName) {
                $q->where('last_name', 'LIKE', "%{$searchEmployeeLastName}%");
            });
        }
        if (!empty($searchEmployeeFirstName)) {
            $searchWithParams->whereHas('employee', function ($q) use ($searchEmployeeFirstName) {
                $q->where('first_name', 'LIKE', "%{$searchEmployeeFirstName}%");
            });
        }
        if (!empty($searchLessonName)) {
            $searchWithParams->where('lesson_name', 'LIKE', "%{$searchLessonName}%");
        }
        if (!empty($searchPlace)) {
            $searchWithParams->where('classroom', 'LIKE', "%{$searchPlace}%");
        }

        if (!empty($query)) {
            $searchWithParams->whereHas('employee', function ($q) use ($query) {
                $q->where('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('group', 'LIKE', "%{$query}%");
            })
                ->orWhere('lesson_name', 'LIKE', "%{$query}%")
                ->orWhere('classroom', 'LIKE', "%{$query}%");
        }

        if ($sortBy === 'employee_id') {
            $lessons1 = $searchWithParams->join('users as u1', 'lessons.employee_id', '=', 'u1.id')
                ->where('student_id', Auth::user()->id)
                ->orderBy('u1.last_name', $sortOrder)
                ->select('lessons.*');
        } else {
            $lessons1 = $searchWithParams->where('student_id', Auth::user()->id)
                ->orderBy($sortBy, $sortOrder);
        }

        $lessons = $lessons1->paginate();

        $lessonCount = Lesson::count();

        $params = [
            'query' => $query,
            'searchEmployeeLastName' => $searchEmployeeLastName,
            'searchEmployeeFirstName' => $searchEmployeeFirstName,
            'searchLessonName' => $searchLessonName,
            'searchPlace' => $searchPlace,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page
        ];

        return view('user.attendance-student', [
            'lessons' => $lessons,
            'lessonCount' => $lessonCount,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page,
            'params' => $params,
            'lessons1' => $lessons1
        ]);
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
