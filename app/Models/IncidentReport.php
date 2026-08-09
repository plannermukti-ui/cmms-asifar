<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
class IncidentReport extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['tool_transaction_id', 'mechanic_id', 'kronologi', 'status_approval', 'approved_by'];
    public function transaction() { return $this->belongsTo(ToolTransaction::class, 'tool_transaction_id'); }
    public function mechanic() { return $this->belongsTo(Mechanic::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}