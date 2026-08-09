<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WoSubtaskPart extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['wo_subtask_id', 'part_id', 'qty', 'satuan'];

    public function subtask() { return $this->belongsTo(WoSubtask::class, 'wo_subtask_id'); }
    public function part() { return $this->belongsTo(Part::class); }
}