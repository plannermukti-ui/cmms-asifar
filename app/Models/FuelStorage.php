<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelStorage extends Model
{
    use HasFactory, SoftDeletes, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function receivings()
    {
        return $this->hasMany(FuelReceiving::class);
    }

    public function transfersOut()
    {
        return $this->hasMany(FuelTransfer::class, 'source_storage_id');
    }

    public function transfersIn()
    {
        return $this->hasMany(FuelTransfer::class, 'destination_storage_id');
    }

    public function truckFillings()
    {
        return $this->hasMany(FuelTruckFilling::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(FuelStockLog::class, 'reference_id')->where('reference_type', 'fuel_storage');
    }

    public function flowmeterAdjustments()
    {
        return $this->hasMany(FuelFlowmeterAdjustment::class, 'device_id')->where('device_type', 'fuel_storage');
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
