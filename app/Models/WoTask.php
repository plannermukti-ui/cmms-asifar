<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WoTask extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['work_order_id', 'problem', 'component_group_id', 'date_problem', 'status'];
    protected $casts = ['date_problem' => 'datetime'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function componentGroup() { return $this->belongsTo(ComponentGroup::class); }
    public function subtasks() { return $this->hasMany(WoSubtask::class); }
}