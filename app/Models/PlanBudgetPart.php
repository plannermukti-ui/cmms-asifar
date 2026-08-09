<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Model;

class PlanBudgetPart extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'plan_budget_unit_id',
        'part_id',
        'qty',
        'price',
        'total_price'
    ];

    public function planBudgetUnit()
    {
        return $this->belongsTo(PlanBudgetUnit::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
