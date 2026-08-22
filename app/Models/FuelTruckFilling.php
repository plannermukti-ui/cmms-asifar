<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelTruckFilling extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'fill_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function storage()
    {
        return $this->belongsTo(FuelStorage::class, 'fuel_storage_id');
    }

    public function fuelTruck()
    {
        return $this->belongsTo(FuelTruck::class, 'fuel_truck_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public static function generateRefillNumber(): string
    {
        $prefix = 'FTF-' . date('Ym') . '-';
        $last = self::withoutGlobalScopes()->where('refill_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->refill_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        return $prefix . $newNum;
    }
}
