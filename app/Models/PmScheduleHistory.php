<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmScheduleHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pm_schedule_id',
        'hm_service',
        'executed_at',
        'work_order_no',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'executed_at' => 'date',
    ];

    public function pmSchedule()
    {
        return $this->belongsTo(PmSchedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workOrder()
    {
        // This links based on string since WO NO is unique and it allows clicking. 
        // If we want a strict relationship, it should be work_order_id, but the user requested "insert No WO".
        return $this->belongsTo(WorkOrder::class, 'work_order_no', 'no_wo');
    }
}
