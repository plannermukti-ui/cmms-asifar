<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToSite;

class PmTemplate extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory, BelongsToSite;

    protected $fillable = [
        'unit_model_id',
        'site_id',
        'name',
        'interval_type',
        'interval_value',
        'opr_hrs_per_day',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function unitModel()
    {
        return $this->belongsTo(UnitModel::class);
    }

    public function tasks()
    {
        return $this->hasMany(PmTemplateTask::class)->orderBy('sequence');
    }

    public function schedules()
    {
        return $this->hasMany(PmSchedule::class);
    }
}
