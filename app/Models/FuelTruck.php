<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelTruck extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function fillings()
    {
        return $this->hasMany(FuelTruckFilling::class);
    }

    public function distributionShifts()
    {
        return $this->hasMany(FuelDistributionShift::class);
    }

    public function distributions()
    {
        return $this->hasMany(FuelDistribution::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(FuelStockLog::class, 'reference_id')->where('reference_type', 'fuel_truck');
    }

    public function flowmeterAdjustments()
    {
        return $this->hasMany(FuelFlowmeterAdjustment::class, 'device_id')->where('device_type', 'fuel_truck');
    }

    public function getFillPercentageAttribute(): float
    {
        if ($this->capacity <= 0) return 0.0;
        return round(min(100, max(0, ($this->current_stock / $this->capacity) * 100)), 1);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $pct = $this->fill_percentage;
        if ($pct <= 15) return 'bg-danger text-white';
        if ($pct <= 30) return 'bg-warning text-dark';
        return 'bg-success text-white';
    }
}
