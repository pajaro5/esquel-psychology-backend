<?php

namespace App\Models\Cecy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPlanification extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    protected $table = 'cecy.detail_planifications';

    protected $fillable = [
        'end_time',
        'observation',
        'plan_ended_at',
        'registrations_left',
        'start_time',
    ];

    // Relationships
    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certificateable');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function day()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function paralel()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function planification()
    {
        return $this->belongsTo(Planification::class);
    }

    public function workday()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function detailInstructors()
    {
        return $this->hasMany(DetailInstructor::class);
    }

    public function photographicRecords()
    {
        return $this->hasMany(PhotograficRecord::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'detail_planification_instructor', 'detail_planification_id', 'instructor_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    // Mutators

    // Scopes
    public function scopeCustomOrderBy($query, $sorts)
    {
        if (!empty($sorts[0])) {
            foreach ($sorts as $sort) {
                $field = explode('-', $sort);
                if (empty($field[0]) && in_array($field[1], $this->fillable)) {
                    $query = $query->orderByDesc($field[1]);
                } else if (in_array($field[0], $this->fillable)) {
                    $query = $query->orderBy($field[0]);
                }
            }
            return $query;
        }
    }

    public function scopePlanificationplanification($query, $planification)
    {
        if ($planification) {
            return $query->orWhere('planification_id', $planification->id);
        }
    }
}
