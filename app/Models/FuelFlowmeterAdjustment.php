<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelFlowmeterAdjustment extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'incident_date' => 'date',
        'signed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function managerUser()
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function device()
    {
        if ($this->device_type === 'fuel_storage') {
            return $this->belongsTo(FuelStorage::class, 'device_id');
        }
        return $this->belongsTo(FuelTruck::class, 'device_id');
    }

    public static function generateAdjustmentNumber(): string
    {
        $prefix = 'BAF-' . date('Ym') . '-';
        $last = self::withoutGlobalScopes()->where('adjustment_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->adjustment_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        return $prefix . $newNum;
    }
}
