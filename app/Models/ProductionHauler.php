<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionHauler extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'production_fleet_id',
        'hauler_id',
        'payload',
        'hourly_ritasi',
        'total_ritasi',
    ];

    protected $casts = [
        'hourly_ritasi' => 'array',
    ];

    public function fleet()
    {
        return $this->belongsTo(ProductionFleet::class, 'production_fleet_id');
    }

    public function hauler()
    {
        return $this->belongsTo(MasterUnit::class, 'hauler_id');
    }
}
