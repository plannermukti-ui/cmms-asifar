<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmTemplateTask extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $guarded = [];

    public function pmTemplate()
    {
        return $this->belongsTo(PmTemplate::class);
    }

    public function subtasks()
    {
        return $this->hasMany(PmTemplateSubtask::class)->orderBy('sequence');
    }
}
