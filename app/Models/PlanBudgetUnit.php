<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanBudgetUnit extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'plan_budget_id',
        'master_unit_id',
        'target_pa',
        'planned_cost'
    ];

    public function planBudget()
    {
        return $this->belongsTo(PlanBudget::class);
    }

    public function unit()
    {
        return $this->belongsTo(MasterUnit::class, 'master_unit_id')->withoutGlobalScope('active');
    }

    public function parts()
    {
        return $this->hasMany(PlanBudgetPart::class);
    }
}
