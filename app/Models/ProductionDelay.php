<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDelay extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'production_id',
        'production_fleet_id',
        'start_time',
        'end_time',
        'delay_code',
        'remarks',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function fleet()
    {
        return $this->belongsTo(ProductionFleet::class, 'production_fleet_id');
    }
}
