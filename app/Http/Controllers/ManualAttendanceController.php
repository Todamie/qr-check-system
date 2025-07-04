<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManualAttendanceController extends LessonController
{
    public function manual_attend(Request $request)
    {
        $user = User::where('id', '4')->first();

        $client = new Client();
        $response = $client->get("http://192.168.16.105:86/", [
            'query' => [
                'group' => $user->group
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        foreach ($data as $array) {
            $lessons_list[] = $array['предмет'];
        }

        $lessons_list = array_values(array_unique($lessons_list));
        
        sort($lessons_list);

        $employees = User::where('last_name', 'not like', 'TYUIU')->with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'employee');
            })->orderBy('last_name')
            ->get();

        $students = User::where('last_name', 'not like', 'TYUIU')->with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })->orderBy('last_name')
            ->get();

        return view('user.manual-attendance', [
            'students' => $students,
            'employees' => $employees,
            'lessons_list' => $lessons_list,
        ]);

    }

    public function manual_attend_post(Request $request){
            
    $attributes = $request->validate([
            'student_id' => ['required'],
            'employee_id' => ['required'],
            'lesson_name' => ['required', 'string'],
            'created_at' => ['required', 'date'],
            'classroom' => ['string'],
        ]);

        Lesson::create(array_merge($attributes, ['attend_type' => 'manual']));

        return back()->with('success', 'Отметка создана');
    }

    public function getLessonsByStudent($student_id)
    {
        $user = User::findOrFail($student_id);

        $client = new Client();
        $response = $client->get("http://192.168.16.105:86/", [
            'query' => [
                'group' => $user->group
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $lessons_list = [];
        foreach ($data as $array) {
            $lessons_list[] = $array['предмет'];
        }
        $lessons_list = array_values(array_unique($lessons_list));
        sort($lessons_list);

        return response()->json([
            'group' => $user->group,
            'lessons' => $lessons_list,
        ]);
    }
}