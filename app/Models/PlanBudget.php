<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PlanBudget extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('plan_budget')
            ->setDescriptionForEvent(fn(string $eventName) => "Plan Budget for {$this->period} has been {$eventName}");
    }

    protected $fillable = [
        'site_id',
        'period',
        'status',
        'created_by'
    ];

    public function units()
    {
        return $this->hasMany(PlanBudgetUnit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
