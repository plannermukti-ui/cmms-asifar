<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmSchedule extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory, BelongsToSite;

    protected $guarded = [];

    protected $casts = [
        'next_due_date' => 'date',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class)->withoutGlobalScope('active');
    }

    public function pmTemplate()
    {
        return $this->belongsTo(PmTemplate::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function histories()
    {
        return $this->hasMany(PmScheduleHistory::class)->orderBy('executed_at', 'desc');
    }

    public function latestHistory()
    {
        return $this->hasOne(PmScheduleHistory::class)->latestOfMany('executed_at');
    }

    /**
     * Estimated next due date fallback if not explicitly stored in database
     */
    public function getEstimatedNextDueDateAttribute()
    {
        if (!empty($this->attributes['next_due_date'])) {
            return \Carbon\Carbon::parse($this->attributes['next_due_date']);
        }

        $currentHm = $this->masterUnit?->latestHourMeter?->hm ?? $this->last_executed_value ?? 0;
        $oprHrs = $this->pmTemplate?->opr_hrs_per_day ?? 20;
        if ($oprHrs <= 0) {
            $oprHrs = 20;
        }

        $hrsToGo = ($this->next_due_value ?? 0) - $currentHm;
        $baseDate = $this->masterUnit?->latestHourMeter?->date 
            ?? $this->latestHistory?->executed_at 
            ?? $this->updated_at 
            ?? now();

        if ($hrsToGo <= 0) {
            return \Carbon\Carbon::parse($baseDate);
        }

        $daysToGo = $hrsToGo / $oprHrs;
        return \Carbon\Carbon::parse($baseDate)->addDays((int) ceil($daysToGo));
    }

    /**
     * Determine dynamic status
     */
    public function getStatusAttribute()
    {
        $currentHm = $this->masterUnit?->latestHourMeter?->hm;
        if ($currentHm !== null && $this->next_due_value !== null) {
            if ($currentHm >= $this->next_due_value) {
                return 'Overdue';
            }
            if ($currentHm >= ($this->next_due_value - 50)) {
                return 'Due';
            }
        }
        return $this->status_jadwal ?? 'Upcoming';
    }
}
