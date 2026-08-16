<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmTemplateSubtask extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $guarded = [];

    public function pmTemplateTask()
    {
        return $this->belongsTo(PmTemplateTask::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'pm_template_subtask_parts')->withPivot('quantity')->withTimestamps();
    }
}
