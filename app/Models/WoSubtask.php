<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WoSubtask extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['wo_task_id', 'action', 'date_action', 'date_finish', 'duration_hours', 'breakdown_type_id', 'status'];
    protected $casts = [
        'date_action' => 'datetime',
        'date_finish' => 'datetime',
        'duration_hours' => 'decimal:2',
    ];

    public function task() { return $this->belongsTo(WoTask::class, 'wo_task_id'); }
    public function breakdownType() { return $this->belongsTo(BreakdownType::class); }
    public function manpower() { return $this->hasMany(WoSubtaskManpower::class); }
    public function parts() { return $this->hasMany(WoSubtaskPart::class); }
    public function tools() { return $this->hasMany(WoSubtaskTool::class); }
}