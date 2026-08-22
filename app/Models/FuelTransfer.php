<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelTransfer extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'transfer_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function sourceStorage()
    {
        return $this->belongsTo(FuelStorage::class, 'source_storage_id');
    }

    public function destinationStorage()
    {
        return $this->belongsTo(FuelStorage::class, 'destination_storage_id');
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

    public static function generateTransferNumber(): string
    {
        $prefix = 'TR-' . date('Ym') . '-';
        $last = self::withoutGlobalScopes()->where('transfer_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->transfer_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        return $prefix . $newNum;
    }
}
