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

    protected $fillable = [
        'wo_subtask_id', 'part_id', 'qty', 'satuan',
        'part_status', 'mol_pr', 'order_status', 
        'swap_type', 'swap_unit_id', 'swap_status', 'swap_remarks'
    ];

    public function subtask() { return $this->belongsTo(WoSubtask::class, 'wo_subtask_id'); }
    public function part() { return $this->belongsTo(Part::class); }
    public function swapUnit() { return $this->belongsTo(MasterUnit::class, 'swap_unit_id'); }
}