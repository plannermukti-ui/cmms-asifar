<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionFleet extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'production_id',
        'digger_id',
        'material_type',
        'distance',
        'target_bcm_per_hour',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function digger()
    {
        return $this->belongsTo(MasterUnit::class, 'digger_id');
    }

    public function haulers()
    {
        return $this->hasMany(ProductionHauler::class, 'production_fleet_id');
    }

    public function delays()
    {
        return $this->hasMany(ProductionDelay::class, 'production_fleet_id');
    }
}
