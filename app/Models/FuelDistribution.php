<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelDistribution extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'dispense_time' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function shiftSession()
    {
        return $this->belongsTo(FuelDistributionShift::class, 'fuel_distribution_shift_id');
    }

    public function fuelTruck()
    {
        return $this->belongsTo(FuelTruck::class);
    }

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
