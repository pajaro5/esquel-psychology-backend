<?php

namespace App\Http\Resources\V1\Cecy\Planifications;

use App\Http\Resources\V1\Cecy\Authorities\AuthorityResource;
use App\Http\Resources\V1\Cecy\Catalogues\CatalogueResource;
use App\Http\Resources\V1\Cecy\Courses\CourseResource;
use App\Http\Resources\V1\Cecy\Instructors\InstructorResource;
use App\Http\Resources\V1\Cecy\SchoolPeriods\SchoolPeriodResource;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Http\Re

class PlanificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'area' => CatalogueResource::make($this->area),
            'course' => CourseResource::make($this->course),
            'responsibleCourse' => InstructorResource::make($this->responsibleCourse),
            'responsibleCecy' => AuthorityResource::make($this->responsibleCecy),
            'detailSchoolPeriods' => DetailSchoolPeriodResource::make($this->detail_school_period_id),
            // 'sector' => CatalogueResource::make($this->sector_id),
            'state' => CatalogueResource::make($this->state_id),
            'code' => $this->code,
            'endedAt' => $this->ended_at,
            'needs' => $this->needs,
            'observations' => $this->observations,
            'startedAt' => $this->started_at,
        ];
    }
}
