<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class WoSubtaskTool extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['wo_subtask_id', 'tool_transaction_id'];

    public function subtask() { return $this->belongsTo(WoSubtask::class, 'wo_subtask_id'); }
    public function toolTransaction() { return $this->belongsTo(ToolTransaction::class); }
}