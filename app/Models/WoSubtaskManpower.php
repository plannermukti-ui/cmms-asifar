<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WoSubtaskManpower extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $table = 'wo_subtask_manpower';
    protected $fillable = ['wo_subtask_id', 'mechanic_id'];

    public function subtask() { return $this->belongsTo(WoSubtask::class, 'wo_subtask_id'); }
    public function mechanic() { return $this->belongsTo(Mechanic::class); }
}