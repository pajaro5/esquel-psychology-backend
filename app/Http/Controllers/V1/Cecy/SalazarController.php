<?php

namespace App\Http\Controllers\V1\Cecy;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Cecy\ProfileInstructorCourses\ProfileInstructorCourseCollection;
use App\Http\Resources\V1\Cecy\ProfileInstructorCourses\ProfileInstructorCourseResource;
use Illuminate\Http\Request;
use App\Models\Cecy\ProfileInstructorCourse;
use App\Models\Cecy\Course;    


class SalazarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show')->only(['show']);
    }

      public function showCurricularDesign()
      {
        // trae la informacion de diseño curricular 
        $course = Course::where('course_id', $request->course()->id)->get();

    $detailPlanifications = $course
        ->detailPlanifications()
        ->planifications()
        ->course()
        ->paginate($request->input('per_page'));

    return (new DetailPlanificationCurricularDesign($detailPlanifications))
        ->additional([
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ]);
    }
      

      public function showAttendenceEvaluationRecord()
      {
         // trae la informacion de registro asistencia-evaluacion
         $course = Course::where('course_id', $request->course()->id)->get();

    $detailPlanifications = $course
        ->detailPlanifications()
        ->planifications()
        ->course()
        ->registration()
        ->attendence()
        ->paginate($request->input('per_page'));

    return (new DetailPlanificationEvaluationRecord($detailPlanifications))
        ->additional([
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ]);
    }
      
  
      public function showFinalCourseReport()
      {
       // trae la informacion del informe final del curso 
       $responsibleCourse= Instructor::where('user_id', $request->user()->id)->get();

    $detailPlanifications = $course
        ->detailPlanifications()
        ->planifications()
        ->course()
        ->paginate($request->input('per_page'));

    return (new DetailPlanificationFinalCourseReport($detailPlanifications))
        ->additional([
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ]);
    }
         
      }

  
    