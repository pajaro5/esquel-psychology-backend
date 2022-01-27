<?php

namespace App\Http\Controllers\V1\Cecy;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Cecy\Registrations\RegisterStudentCollection;
use App\Models\Cecy\Attendance;
use App\Models\Cecy\Catalogue;
use App\Models\Cecy\Course;
use App\Models\Cecy\DetailPlanification;
use App\Models\Cecy\Instructor;
use App\Models\Cecy\PhotograficRecord;
use App\Models\Cecy\Planification;
use App\Models\Core\File;
use App\Http\Requests\V1\Cecy\Attendance\DestroysAttendanceTeacherRequest;
use App\Http\Requests\V1\Cecy\Attendance\GetAttendanceTeacherRequest;
use App\Http\Requests\V1\Cecy\Attendance\ShowAttendanceTeacherRequest;
use App\Http\Requests\V1\Cecy\Attendance\StoreAttendanceTeacherRequest;
use App\Http\Requests\V1\Cecy\Attendance\UpdateAttendanceTeacherRequest;
use App\Http\Requests\V1\Cecy\Attendances\GetAttendanceDetailPlanificationRequest;
use App\Http\Requests\V1\Cecy\Certificates\ShowParticipantsRequest;
use App\Http\Requests\V1\Cecy\Planifications\GetPlanificationByResponsableCourseRequest;
use App\Http\Requests\V1\Core\Files\UploadFileRequest;
use App\Http\Requests\V1\Core\Images\UploadImageRequest;
use App\Http\Resources\V1\Cecy\Attendances\AttendanceCollection;
use App\Http\Resources\V1\Cecy\Attendances\AttendanceDetailPlanificationCollection;
use App\Http\Resources\V1\Cecy\Attendances\AttendanceResource;
use App\Http\Resources\V1\Cecy\Authorities\DetailAttendanceCollection;
use App\Http\Resources\V1\Cecy\DetailPlanifications\DetailPlanificationCollection;
use App\Http\Resources\V1\Cecy\Registrations\RegisterStudentResource;

use Illuminate\Http\Client\Request;

class SantillanController extends Controller
{
   /* public function __construct()
    {
        $this->middleware('permission:store-authorities_teacher')->only(['store']);
        $this->middleware('permission:update-authorities_teacher')->only(['update']);
        $this->middleware('permission:delete-authorities_teacher')->only(['destroy', 'destroys']);
    }*/

    //ver todas las asistencias
    public function getAttendanceTeacher(GetAttendanceTeacherRequest $request)
    {
        $attendance =  Planification::where([['course_id', $request->input('course.id')]])->get();

        return (new DetailAttendanceCollection($attendance))
            ->additional([
                'msg' => [
                    'sumary' => 'consulta exitosa',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }
    //asistencias de los estudiantes de un curso
    public  function ShowParticipantCourse(ShowParticipantsRequest $request){

        /*$participants = Course::where('course_id', $request->course()->id)->get();

        $registration = $participants
            ->detailAttendaces()
            ->registrations()
            ->attendances()
            ->participants()
            ->users()
            ->detailPlanifications()
            ->planifications()
            ->course()
            ->paginate($request->input('per_page'));*/

        $planification = Course::whereIN('course_id', $request->course()->id)->get();
        $detailPlanifications =  $planification->detailPlanifications()->get();
        $registrations = $detailPlanifications->registration()->get();
        $detialAttendance = $registrations->detailAttendance()->get();
        $attendances =  $detialAttendance->attendacne()->get();
        $participants = $attendances->participant()->get();
        $users = $participants->user()->get();

        return (new DetailAttendanceCollection($users))
            ->additional([
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }
    //estudiantes de un curso y sus notas
    public  function ShowParticipantGrades(ShowParticipantsRequest $request){

        $planification = Course::whereIN('course_id', $request->course()->id)->get();
        $detailPlanifications =  $planification->detailPlanifications()->get();
        $registrations = $detailPlanifications->registration()->get();
        $participants = $registrations->participant()->get();
        $users = $participants->user()->get();
        /*$registration = $participants
            ->registrations()
            ->participants()
            ->users()
            ->detailPlanifications()
            ->planifications()
            ->course()
            ->paginate($request->input('per_page'));*/

        return (new RegisterStudentCollection($users))
            ->additional([
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }
    //cursos de un docente instructor
    public function showInstructorCourse(GetPlanificationByResponsableCourseRequest $request){

        //$responsableCourse = Planification::where('responsable_course_id', $request->planification()->id)->get();
        /*$detailPlanification = $responsableCourse
            ->detailPlanifications()
            ->classRooms()
            ->planifications()
            ->intructors()
            ->users()
            ->course()
            ->paginate($request->input('per_page'));*/

        $instructor = Instructor::FirstWhere('user_id',$request->user()->id);
        $planification =  $instructor->planification()->get();
        $courses = $planification->courses()->get();

        return (new DetailPlanificationCollection($courses))
            ->additional([
                'msg' => [
                    'summary' => 'Consulta exitosa',
                    'detail' => '',
                    'code' => '200'
                ]
            ])
            ->response()->setStatusCode(200);

    }
    //traer fechas y horarios de un curso
    public function getDetailPlanification(GetAttendanceDetailPlanificationRequest $request){

        $planification = Course::whereIN('course_id', $request->course()->id)->get();
        $detailPlanifications =  $planification->detailPlanifications()->get();


        return (new AttendanceDetailPlanificationCollection($detailPlanifications))
            ->additional([
                    'msg' => [
                        'sumary' => 'consulta exitosa',
                        'detail' => '',
                        'code' => '200'
                    ]
                ])
            ->response()->setStatusCode(200);

    }

    //crear una asistencia a partir de las fechas y horarios de detalle planificacion.
    public function storeAttendanceTeacher(StoreAttendanceTeacherRequest $request)
    {
        $attendance = new Attendance();

        $attendance->detailPlanification()
            ->associate(DetailPlanification::find($request->input('detail_planification.id')));

        $attendance->duration = $request->input('duration');

        $attendance->register_at = $request->input('register_at');

        $attendance->save();

        return (new AttendanceCollection($attendance))
            ->additional([
                'msg' => [
                    'summary' => 'Registro Creado',
                    'detail' => '',
                    'code' => '200'
                ]
            ])
            ->response()->setStatusCode(200);

    }

    //ver asistencia una por una
    public function showAttendanceTeacher(ShowAttendanceTeacherRequest $request )
    {
        $attendance =  Attendance::where([['registered_at', $request->input('registered_at')]])->get();

        return (new AttendanceResource($attendance))
            ->additional([
                'msg' => [
                    'summary' => 'Asistencias encontradas',
                    'detail' => '',
                    'code' => '200'
                ]
            ])
            ->response()->setStatusCode(200);

    }

    //editar o actualizar una asistencia
    public function updateAttendanceTeacher(UpdateAttendanceTeacherRequest $attendance, Request $request)
    {

        $attendance->detailPlanification()
            ->associate(DetailPlanification::find($request->input('detail_planification.id')));

        $attendance->duration = $request->input('duration');

        $attendance->register_at = $request->input('register_at');

        $attendance->save();

        return (new AttendanceResource($attendance))
            ->additional([
                'msg' => [
                    'summary' => 'Registro actualizado',
                    'detail' => '',
                    'code' => '200'
                ]
            ])
            ->response()->setStatusCode(200);

    }
    //eliminar una asistencia

    public function destroysAttendanceTeacher(DestroysAttendanceTeacherRequest $request)
    {
        $attendance = Attendance::whereIn('id', $request->input('ids'))->get();
        Attendance::destroy($request->input('ids'));

        return (new AttendanceResource($attendance))
            ->additional([
                'msg' => [
                    'summary' => 'Asistencia eliminada',
                    'detail' => '',
                    'code' => '200'
                ]
            ])
            ->response()->setStatusCode(200);

    }
    /*******************************************************************************************************************
     * FILES
     ******************************************************************************************************************/

    //subir notas de los estudiantes
    public function uploadFile(UploadFileRequest $request, FIle $file)
    {
        return $file->uploadFile($request);
    }


    //descargar plantilla de las notas
    public function downloadFile(Catalogue $catalogue, File $file)
    {
        return $catalogue->downloadFile($file);
    }

    //previsualizar la platilla de notas
    public function showFile(Catalogue $catalogue, File $file)
    {
        return $catalogue->showFile($file);
    }

    //eliminar el archivo existente para poder cargar de nuevo
    public function destroyFile(Catalogue $catalogue, File $file)
    {
        return $catalogue->destroyFile($file);
    }


    /*******************************************************************************************************************
     * IMAGES
     ******************************************************************************************************************/
    //subir evidencia fotografica
    public function uploadImage(UploadImageRequest $request, PhotograficRecord $photograficRecord)
    {
        $storagePath = storage_path('app/private/images/');
        $image = InterventionImage::make($image);
        $path = $storagePath . time() . '.jpg';
        $image->save($path, 75);

        return $photograficRecord->uploadImage($request);

    }

}
