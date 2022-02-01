<?php

namespace App\Http\Requests\V1\Cecy\Courses;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileInstructorCourseRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }
  public function rules()
  {
    return [
      'course.id' => ['required', 'integer'],
      'requiredExperiences' => ['required'],
      'requiredKnowledges' => ['required'],
      'requiredSkills' => ['required'],
    ];
  }

  public function attributes()
  {
    return [
      'course.id' => 'Fk de curso',
      'requiredExperiences' => 'Experiencias del instructor',
      'requiredKnowledges' => 'Conocimientos del instructor',
      'requiredSkills' => 'Habilidades del instructor',
    ];
  }
}
