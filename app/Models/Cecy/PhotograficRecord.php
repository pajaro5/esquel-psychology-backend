<?php

namespace App\Models\Cecy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as Auditing;

class PhotograficRecord extends Model
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    protected $table = 'cecy.photografic_records';

    protected $fillable = [
        'url_imagen',
        'description',
        'number_week',
        'week_at'
    ];

    // Relationships
    public function detailPlanifaction()
    {
        return $this->belongsTo(DetailPlanification::class);
    }

    // Mutators
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }


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
}